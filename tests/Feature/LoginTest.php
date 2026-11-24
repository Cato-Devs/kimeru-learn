<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_login_page_loads(): void
    {
        $this->get('/login')->assertOk()->assertSee('Editor login');
    }

    public function test_an_editor_can_log_in_and_out(): void
    {
        $user = User::factory()->create(['email' => 'editor@example.com', 'password' => 'correct-horse']);

        $this->post('/login', ['email' => 'editor@example.com', 'password' => 'correct-horse'])
            ->assertRedirect('/words');
        $this->assertAuthenticatedAs($user);

        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_login_returns_to_the_page_the_editor_wanted(): void
    {
        User::factory()->create(['email' => 'editor@example.com', 'password' => 'correct-horse']);

        $this->get('/words/greetings/create')->assertRedirect('/login');

        $this->post('/login', ['email' => 'editor@example.com', 'password' => 'correct-horse'])
            ->assertRedirect('/words/greetings/create');
    }

    public function test_a_wrong_password_is_rejected_without_saying_which_part_was_wrong(): void
    {
        User::factory()->create(['email' => 'editor@example.com', 'password' => 'correct-horse']);

        $this->from('/login')
            ->post('/login', ['email' => 'editor@example.com', 'password' => 'wrong'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email' => 'Those details don\'t match an editor account.']);

        // Follow the redirect back to the form: the message is shown, the email is kept.
        $this->from('/login')
            ->followingRedirects()
            ->post('/login', ['email' => 'editor@example.com', 'password' => 'wrong'])
            ->assertSee('Those details don\'t match an editor account.')
            ->assertSee('value="editor@example.com"', false)
            ->assertSee('aria-invalid="true"', false);

        $this->assertGuest();
    }

    public function test_login_is_limited_to_5_attempts_a_minute(): void
    {
        foreach (range(1, 5) as $attempt) {
            $this->post('/login', ['email' => 'someone@example.com', 'password' => 'guess'.$attempt])
                ->assertRedirect();
        }

        $this->post('/login', ['email' => 'someone@example.com', 'password' => 'guess6'])
            ->assertStatus(429); // Too Many Requests
    }

    public function test_the_password_is_stored_hashed(): void
    {
        $user = User::factory()->create(['password' => 'correct-horse']);

        $this->assertNotSame('correct-horse', $user->getAttributes()['password']);
        $this->assertStringStartsWith('$2y$', $user->getAttributes()['password']); // bcrypt
    }

    public function test_there_is_no_public_sign_up_page(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_the_editor_login_link_is_only_shown_to_guests(): void
    {
        $this->get('/')->assertSee('Editor login');

        $this->actingAs(User::factory()->create(['name' => 'Cato']))
            ->get('/')
            ->assertDontSee('Editor login')
            ->assertSee('Editor: Cato')
            ->assertSee('Log out');
    }
}

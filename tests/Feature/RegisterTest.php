<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private function signUp(array $overrides = []): TestResponse
    {
        return $this->post('/register', $overrides + [
            'name' => 'Wanja',
            'email' => 'wanja@example.com',
            'password' => 'correct-horse',
            'password_confirmation' => 'correct-horse',
        ]);
    }

    public function test_the_sign_up_page_loads(): void
    {
        $this->get('/register')->assertOk()->assertSee('Sign up')->assertSee('Confirm password');
    }

    public function test_a_learner_can_sign_up_and_is_logged_in(): void
    {
        $this->signUp()
            ->assertRedirect('/my-scores')
            ->assertSessionHas('status', 'Welcome, Wanja. Your quiz scores will be saved here.');

        $user = User::where('email', 'wanja@example.com')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertSame(User::LEARNER, $user->fresh()->role);
        $this->assertTrue(Hash::check('correct-horse', $user->password));
    }

    public function test_sign_up_cannot_make_an_editor(): void
    {
        $this->signUp(['role' => 'editor']);

        $user = User::where('email', 'wanja@example.com')->firstOrFail();
        $this->assertSame(User::LEARNER, $user->fresh()->role);
        $this->assertFalse($user->fresh()->isEditor());
    }

    public function test_sign_up_checks_every_field(): void
    {
        $this->from('/register')
            ->post('/register', ['name' => '', 'email' => 'not-an-email', 'password' => 'short', 'password_confirmation' => 'other'])
            ->assertRedirect('/register')
            ->assertSessionHasErrors(['name', 'email', 'password']);

        $this->assertSame(0, User::count());
        $this->assertGuest();
    }

    public function test_an_email_can_only_be_used_once(): void
    {
        User::factory()->create(['email' => 'wanja@example.com']);

        $this->signUp()->assertSessionHasErrors('email');
        $this->assertSame(1, User::count());
    }

    public function test_logged_in_users_cannot_open_sign_up(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/register')
            ->assertRedirect('/');
    }
}

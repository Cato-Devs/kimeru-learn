<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateEditorTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_editor_with_a_hashed_password(): void
    {
        $this->artisan('editor:create')
            ->expectsQuestion('Name', 'Cato')
            ->expectsQuestion('Email', 'cato@example.com')
            ->expectsQuestion('Password (at least 8 characters)', 'correct-horse')
            ->expectsQuestion('Confirm password', 'correct-horse')
            ->assertSuccessful();

        $user = User::where('email', 'cato@example.com')->firstOrFail();
        $this->assertSame('Cato', $user->name);
        $this->assertTrue($user->isEditor());
        $this->assertTrue(Hash::check('correct-horse', $user->password));
    }

    public function test_it_rejects_a_short_or_mismatched_password(): void
    {
        $this->artisan('editor:create')
            ->expectsQuestion('Name', 'Cato')
            ->expectsQuestion('Email', 'cato@example.com')
            ->expectsQuestion('Password (at least 8 characters)', 'short')
            ->expectsQuestion('Confirm password', 'different')
            ->assertFailed();

        $this->assertSame(0, User::count());
    }

    public function test_it_rejects_an_email_that_is_already_used(): void
    {
        User::factory()->create(['email' => 'cato@example.com']);

        $this->artisan('editor:create')
            ->expectsQuestion('Name', 'Someone')
            ->expectsQuestion('Email', 'cato@example.com')
            ->expectsQuestion('Password (at least 8 characters)', 'correct-horse')
            ->expectsQuestion('Confirm password', 'correct-horse')
            ->assertFailed();

        $this->assertSame(1, User::count());
    }
}

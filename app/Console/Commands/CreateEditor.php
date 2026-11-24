<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

/**
 * php artisan editor:create
 *
 * There is no public sign-up page: every user is an editor, and editors are
 * created from the terminal by someone who already has access to the server.
 */
class CreateEditor extends Command
{
    protected $signature = 'editor:create';

    protected $description = 'Create an editor account that can add, edit and delete words';

    public function handle(): int
    {
        $name = $this->ask('Name');
        $email = $this->ask('Email');
        $password = $this->secret('Password (at least 8 characters)'); // typing is hidden
        $confirmation = $this->secret('Confirm password');

        $validator = Validator::make(
            ['name' => $name, 'email' => $email, 'password' => $password, 'password_confirmation' => $confirmation],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        // The User model hashes the password automatically (the 'hashed' cast).
        User::create(['name' => $name, 'email' => $email, 'password' => $password]);

        $this->info("Editor {$email} created. They can now log in at /login.");

        return self::SUCCESS;
    }
}

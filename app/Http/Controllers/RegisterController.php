<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Learner sign-up. Everyone who signs up here is a learner: they can save and
 * see their quiz scores, but can't change the word lists.
 */
class RegisterController extends Controller
{
    /**
     * GET /register: show the sign-up form.
     */
    public function show(): View
    {
        return view('auth.register');
    }

    /**
     * POST /register: create the learner and log them in.
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
        ]);

        // Only name, email and password are fillable, so even if someone adds
        // role=editor to the form it is ignored. The database default makes
        // them a learner. The password is hashed by the User model.
        $user = User::create($data);

        Auth::login($user);
        $request->session()->regenerate(); // new session ID, as at login

        return redirect()->route('my-scores')
            ->with('status', "Welcome, {$user->name}. Your quiz scores will be saved here.");
    }
}

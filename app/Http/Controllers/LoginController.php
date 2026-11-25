<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Login and logout for everyone with an account. Learners sign up at /register
 * (RegisterController); editors are created with "php artisan editor:create".
 */
class LoginController extends Controller
{
    /**
     * GET /login: show the login form.
     */
    public function show(): View
    {
        return view('auth.login');
    }

    /**
     * POST /login: check the email and password.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Auth::attempt hashes the typed password and compares it with the stored hash.
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            // Same message whether the email or the password was wrong,
            // so the form doesn't reveal which emails have accounts.
            return back()
                ->withErrors(['email' => 'Those details don\'t match an account.'])
                ->onlyInput('email');
        }

        // New session ID after login, so an old (possibly stolen) ID is useless.
        $request->session()->regenerate();

        // Back to the page they were trying to open; otherwise editors go to the
        // word lists and learners to their scores.
        $user = $request->user();

        return $user->isEditor()
            ? redirect()->intended(route('words'))->with('status', 'You are logged in as an editor.')
            : redirect()->intended(route('my-scores'))->with('status', "Welcome back, {$user->name}.");
    }

    /**
     * POST /logout: log out and throw the session away.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();     // delete the session data
        $request->session()->regenerateToken(); // new CSRF token

        return redirect()->route('home');
    }
}

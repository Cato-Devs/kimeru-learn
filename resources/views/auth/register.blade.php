@extends('layouts.app')

@section('title', 'Sign up')

@section('content')
    <div class="mx-auto max-w-md px-4 py-8 sm:py-12">
        <h1 class="text-3xl font-semibold text-forest">Sign up</h1>
        <p class="mt-2 text-ink/70">
            Create a free learner account to keep your quiz scores. You can still use everything else without one.
        </p>

        <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
            @csrf

            <div>
                <label for="name" class="field-label">Name</label>
                <input id="name" name="name" type="text" required autofocus autocomplete="name" maxlength="255"
                       value="{{ old('name') }}"
                       @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
                       class="field">
                @error('name')
                    <p id="name-error" class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="field-label">Email</label>
                <input id="email" name="email" type="email" required autocomplete="username" maxlength="255"
                       value="{{ old('email') }}"
                       @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                       class="field">
                @error('email')
                    <p id="email-error" class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="field-label">Password <span class="font-normal text-ink/60">(at least 8 characters)</span></label>
                {{-- Passwords are never put back into the form (no old('password')). --}}
                <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password"
                       @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                       class="field">
                @error('password')
                    <p id="password-error" class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="field-label">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                       class="field">
            </div>

            <button type="submit" class="btn-primary w-full">Create account</button>
        </form>

        <p class="mt-6 text-sm text-ink/70">
            Already have an account? <a href="{{ route('login') }}" class="font-semibold text-forest underline hover:no-underline">Log in</a>
        </p>
    </div>
@endsection

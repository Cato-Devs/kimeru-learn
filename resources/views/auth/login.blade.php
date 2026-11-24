@extends('layouts.app')

@section('title', 'Editor login')

@section('content')
    <div class="mx-auto max-w-md px-4 py-8 sm:py-12">
        <h1 class="text-3xl font-semibold text-forest">Editor login</h1>
        <p class="mt-2 text-ink/70">For team members who add and edit Kimeru words. Learners don't need an account.</p>

        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
            @csrf

            <div>
                <label for="email" class="field-label">Email</label>
                <input id="email" name="email" type="email" required autofocus autocomplete="username"
                       value="{{ old('email') }}"
                       @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                       class="field">
                @error('email')
                    <p id="email-error" class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="field-label">Password</label>
                {{-- The password is never put back into the form (no old('password')). --}}
                <input id="password" name="password" type="password" required autocomplete="current-password"
                       @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                       class="field">
                @error('password')
                    <p id="password-error" class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" value="1" class="h-4 w-4 accent-forest">
                Keep me logged in on this device
            </label>

            <button type="submit" class="btn-primary w-full">Log in</button>
        </form>
    </div>
@endsection

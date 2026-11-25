@php
    $links = [
        'home' => 'Home',
        'numbers' => 'Numbers',
        'quiz' => 'Quiz',
        'words' => 'Words',
        'about' => 'About Kimeru',
    ];
@endphp

<header class="border-b border-forest/10 bg-cream/90 backdrop-blur">
    <div class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-x-6 gap-y-2 px-4 py-4 sm:px-6">
        <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold text-forest">
            <span aria-hidden="true" class="grid h-8 w-8 place-items-center rounded-full bg-forest text-sm text-cream">ĩ</span>
            {{ config('app.name') }}
        </a>

        <nav aria-label="Main">
            <ul class="flex flex-wrap gap-x-4 gap-y-1 text-sm sm:gap-x-6">
                @foreach ($links as $route => $label)
                    <li>
                        <a href="{{ route($route) }}"
                           @class([
                               'hover:text-forest',
                               'font-semibold text-forest' => request()->routeIs($route, $route.'.*'),
                           ])
                           @if (request()->routeIs($route, $route.'.*')) aria-current="page" @endif>
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        {{-- @auth is true when someone is logged in, @guest when nobody is. --}}
        @auth
            <form method="POST" action="{{ route('logout') }}" class="flex items-center gap-3 text-sm">
                @csrf
                <span class="text-ink/60">
                    {{ auth()->user()->isEditor() ? 'Editor' : 'Learner' }}: {{ auth()->user()->name }}
                </span>
                <a href="{{ route('my-scores') }}"
                   @class(['hover:text-forest', 'font-semibold text-forest' => request()->routeIs('my-scores')])
                   @if (request()->routeIs('my-scores')) aria-current="page" @endif>My scores</a>
                <button type="submit" class="font-semibold text-forest underline hover:no-underline">Log out</button>
            </form>
        @endauth
        @guest
            <p class="flex items-center gap-3 text-sm">
                <a href="{{ route('login') }}" class="hover:text-forest">Log in</a>
                <a href="{{ route('register') }}" class="font-semibold text-forest underline hover:no-underline">Sign up</a>
            </p>
        @endguest
    </div>
</header>

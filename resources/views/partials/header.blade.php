@php
    $links = [
        'home' => 'Home',
        'numbers' => 'Numbers',
        'quiz' => 'Quiz',
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
    </div>
</header>

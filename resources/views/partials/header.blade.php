<header class="border-b border-forest/10 bg-cream/90 backdrop-blur">
    <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold text-forest">
            <span aria-hidden="true" class="grid h-8 w-8 place-items-center rounded-full bg-forest text-sm text-cream">ĩ</span>
            {{ config('app.name') }}
        </a>

        <nav aria-label="Main">
            <ul class="flex gap-6 text-sm">
                <li>
                    <a href="{{ route('home') }}"
                       @class([
                           'hover:text-forest',
                           'font-semibold text-forest' => request()->routeIs('home'),
                       ])
                       @if (request()->routeIs('home')) aria-current="page" @endif>
                        Home
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>

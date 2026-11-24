@extends('layouts.app')

@section('title', 'Words')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 sm:py-12">
        <h1 class="text-3xl font-semibold text-forest">Kimeru words</h1>
        <p class="mt-2 max-w-2xl text-ink/70">Everyday words and phrases, grouped by topic.</p>

        @include('partials.status')

        <ul class="mt-8 grid gap-4 sm:grid-cols-2">
            @foreach ($categories as $category)
                <li class="relative rounded-xl border-2 border-forest/20 bg-white p-5 hover:border-forest focus-within:border-forest">
                    <h2 class="text-xl font-semibold">
                        <a href="{{ route('words.index', $category) }}" class="after:absolute after:inset-0 after:rounded-xl">
                            {{ $category->name }}
                        </a>
                    </h2>
                    <p class="mt-1 text-sm text-ink/70">{{ $category->description }}</p>
                    <p class="mt-4 text-sm font-semibold text-forest">
                        {{ $category->words_count }} {{ Str::plural('word', $category->words_count) }}
                    </p>
                </li>
            @endforeach
        </ul>
    </div>
@endsection

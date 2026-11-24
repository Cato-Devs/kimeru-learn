@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <section class="bg-forest text-cream">
        <div class="mx-auto max-w-5xl px-6 py-20">
            <p class="text-sm font-medium uppercase tracking-widest text-ochre">Kĩmĩĩrũ · the language of the Ameru</p>
            <h1 class="mt-3 max-w-2xl text-4xl font-semibold leading-tight sm:text-5xl">
                Learn everyday Kimeru, one small step at a time.
            </h1>
            <p class="mt-5 max-w-2xl text-lg text-cream/80">
                Kimeru Learn helps beginners count from one to ten, practise simple addition,
                greet elders and name common foods, all in Kimeru.
            </p>
        </div>
    </section>

    {{-- What you'll learn --}}
    <section class="mx-auto max-w-5xl px-6 py-16">
        <h2 class="text-2xl font-semibold text-forest">What you'll learn</h2>
        <p class="mt-2 text-ink/70">These sections are being built and will appear here soon.</p>

        <ul class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['title' => 'Numbers 1–10', 'text' => 'Count from one to ten in Kimeru.'],
                ['title' => 'Simple addition', 'text' => 'Add Kimeru numbers and pick the right answer.'],
                ['title' => 'Greetings', 'text' => 'Greet family and elders politely.'],
                ['title' => 'Foods', 'text' => 'Name the foods you eat every day.'],
            ] as $topic)
                <li class="rounded-xl border border-forest/10 bg-white p-5">
                    <h3 class="font-semibold">{{ $topic['title'] }}</h3>
                    <p class="mt-1 text-sm text-ink/70">{{ $topic['text'] }}</p>
                    <span class="mt-4 inline-block rounded-full bg-ochre/15 px-2.5 py-0.5 text-xs font-medium text-ochre-dark">Coming soon</span>
                </li>
            @endforeach
        </ul>
    </section>

    {{-- About teaser --}}
    <section class="border-t border-forest/10 bg-white">
        <div class="mx-auto flex max-w-5xl flex-col gap-6 px-6 py-16 sm:flex-row sm:items-center sm:justify-between">
            <div class="max-w-2xl">
                <h2 class="text-2xl font-semibold text-forest">About Kimeru</h2>
                <p class="mt-3 text-ink/80">
                    Kimeru (Kĩmĩĩrũ) is a Bantu language spoken by the Ameru people on the slopes of Mount Kenya,
                    mostly in Meru and Tharaka-Nithi counties. Learn about its dialects, how it is written and
                    the history behind it.
                </p>
            </div>
            <a href="{{ route('about') }}"
               class="inline-flex shrink-0 items-center gap-2 self-start rounded-full bg-forest px-5 py-2.5 text-sm font-medium text-cream hover:bg-forest/90 sm:self-auto">
                Read about Kimeru <span aria-hidden="true">→</span>
            </a>
        </div>
    </section>
@endsection

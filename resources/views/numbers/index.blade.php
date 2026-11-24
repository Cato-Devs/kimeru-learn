@extends('layouts.app')

@section('title', 'Numbers 1–100')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 sm:py-12">
        <h1 class="text-3xl font-semibold text-forest">Kimeru numbers 1–100</h1>
        <p class="mt-2 max-w-2xl text-ink/70">
            Learn 1–10 first. After that, most numbers follow a pattern:
            <span class="kimeru">ĩkũmi na</span> (ten and …) for 11–19, then
            <span class="kimeru">mĩrongo …</span> (tens) and <span class="kimeru">na</span> (and) for the rest.
        </p>

        <a href="{{ route('quiz') }}" class="btn-primary mt-6">Practise with the quiz</a>

        <ol class="mt-8 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($numbers as $number)
                <li class="flex items-baseline gap-3 rounded-lg border border-forest/10 bg-white px-4 py-3">
                    <span class="w-10 shrink-0 text-right text-lg font-semibold text-ochre-dark">{{ $number->value }}</span>
                    <span class="kimeru">{{ $number->kimeru }}</span>
                </li>
            @endforeach
        </ol>
    </div>
@endsection

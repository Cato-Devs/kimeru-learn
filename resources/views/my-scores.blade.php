@extends('layouts.app')

@section('title', 'My scores')

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-8 sm:py-12">
        <h1 class="text-3xl font-semibold text-forest">My scores</h1>
        <p class="mt-1 text-ink/70">Every quiz round you finish while logged in is saved here.</p>

        @include('partials.status')

        @if ($attempts->isEmpty())
            <div class="mt-8 rounded-xl border border-dashed border-forest/30 bg-white px-5 py-8 text-center">
                <p class="text-ink/70">You haven't finished a quiz yet.</p>
                <a href="{{ route('quiz') }}" class="btn-primary mt-4">Take the quiz</a>
            </div>
        @else
            <dl class="mt-8 grid grid-cols-3 gap-3 text-center">
                <div class="rounded-xl bg-white p-4">
                    <dt class="text-sm text-ink/60">Rounds played</dt>
                    <dd class="mt-1 text-2xl font-semibold text-forest">{{ $attempts->count() }}</dd>
                </div>
                <div class="rounded-xl bg-white p-4">
                    <dt class="text-sm text-ink/60">Best score</dt>
                    <dd class="mt-1 text-2xl font-semibold text-forest">{{ $best }} / {{ $total }}</dd>
                </div>
                <div class="rounded-xl bg-white p-4">
                    <dt class="text-sm text-ink/60">Average</dt>
                    <dd class="mt-1 text-2xl font-semibold text-forest">{{ $average }} / {{ $total }}</dd>
                </div>
            </dl>

            <table class="mt-8 w-full overflow-hidden rounded-xl bg-white text-left">
                <caption class="sr-only">Your quiz rounds, newest first</caption>
                <thead class="bg-forest/5 text-sm text-ink/70">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-medium">When</th>
                        <th scope="col" class="px-5 py-3 font-medium">Quiz</th>
                        <th scope="col" class="px-5 py-3 text-right font-medium">Score</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-forest/10">
                    @foreach ($attempts as $attempt)
                        <tr>
                            <td class="px-5 py-3">
                                <time datetime="{{ $attempt->created_at->toIso8601String() }}">
                                    {{ $attempt->created_at->format('j M Y, H:i') }}
                                </time>
                            </td>
                            <td class="px-5 py-3">{{ ucfirst($attempt->quiz_type) }}</td>
                            <td class="px-5 py-3 text-right font-semibold">{{ $attempt->score }} / {{ $attempt->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('quiz') }}" class="btn-primary mt-8">Play another round</a>
        @endif
    </div>
@endsection

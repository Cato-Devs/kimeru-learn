@extends('layouts.app')

@section('title', 'Quiz')

@php
    // Has this question been checked yet? Everything below depends on it.
    $answered = $question['chosen'] !== null;
    $isCorrect = $answered && $question['chosen'] === $question['answer'];
    $done = $position - 1 + ($answered ? 1 : 0); // questions finished, for the progress bar
@endphp

@section('content')
    <div class="mx-auto max-w-2xl px-4 py-8 sm:py-12">
        <h1 class="sr-only">Kimeru arithmetic quiz</h1>

        {{-- Progress and running score --}}
        <div class="flex items-center justify-between text-sm font-medium text-ink/70 sm:text-base">
            <p>Question {{ $position }} of {{ $total }}</p>
            <p>Score: {{ $score }}</p>
        </div>
        <div class="mt-2 h-2 overflow-hidden rounded-full bg-forest/10" aria-hidden="true">
            <div class="h-full rounded-full bg-forest" style="width: {{ $done / $total * 100 }}%"></div>
        </div>

        {{-- Messages from the server (e.g. the session expired) --}}
        @if (session('status'))
            <p role="status" class="mt-6 rounded-lg bg-ochre/15 px-4 py-3 text-sm text-ochre-dark">
                {{ session('status') }}
            </p>
        @endif

        {{-- The question, in Kimeru words only --}}
        <p class="mt-8 text-sm font-medium uppercase tracking-widest text-ochre-dark">What is the answer?</p>
        <p id="question" class="kimeru mt-2 text-2xl leading-snug sm:text-4xl">
            {{ $words[$question['a']] }}
            <span class="sign">{{ $question['operator'] }}</span>
            {{ $words[$question['b']] }}
            <span class="sign">= ?</span>
        </p>

        @if (! $answered)
            {{-- Step 1: pick a choice and press Check (one POST to the server) --}}
            <form method="POST" action="{{ route('quiz.check') }}" class="mt-8">
                @csrf

                <fieldset aria-describedby="question">
                    <legend class="text-sm font-medium text-ink/70">Choose the answer in Kimeru</legend>

                    @error('choice')
                        <p role="alert" class="mt-2 font-medium text-wrong">{{ $message }}</p>
                    @enderror

                    <div class="mt-3 grid gap-3">
                        @foreach ($question['choices'] as $choice)
                            <label class="choice-card">
                                <input type="radio" name="choice" value="{{ $choice }}" @required($loop->first)>
                                <span class="kimeru text-lg sm:text-2xl">{{ $words[$choice] }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <button type="submit" class="btn-primary mt-6 w-full">Check</button>
            </form>
        @else
            {{-- Step 2: the result, worked out on the server --}}
            <div role="status" aria-live="polite" @class([
                'mt-8 rounded-xl border-l-4 p-4 text-lg',
                'border-correct bg-correct/10' => $isCorrect,
                'border-wrong bg-wrong/10' => ! $isCorrect,
            ])>
                <p>
                    <strong @class(['text-correct' => $isCorrect, 'text-wrong' => ! $isCorrect])>
                        {{ $isCorrect ? '✓ Correct!' : '✗ Incorrect.' }}
                    </strong>
                    {{ $question['a'] }} {{ $question['operator'] }} {{ $question['b'] }} = {{ $question['answer'] }}:
                    <span class="kimeru">{{ $words[$question['answer']] }}</span>
                </p>

                @unless ($isCorrect)
                    <p class="mt-1 text-base">
                        You chose {{ $question['chosen'] }}:
                        <span class="kimeru">{{ $words[$question['chosen']] }}</span>
                    </p>
                @endunless
            </div>

            {{-- The choices again, locked, with the chosen and correct ones marked --}}
            <ul class="mt-6 grid gap-3" aria-label="Answer choices">
                @foreach ($question['choices'] as $choice)
                    @php
                        $isAnswer = $choice === $question['answer'];
                        $isChosen = $choice === $question['chosen'];
                    @endphp
                    <li @class([
                        'choice-card is-locked',
                        'is-right' => $isAnswer,
                        'is-wrong' => $isChosen && ! $isAnswer,
                    ])>
                        <span class="kimeru text-lg sm:text-2xl">{{ $words[$choice] }}</span>
                        <span class="ml-auto shrink-0 text-right text-sm font-semibold">
                            @if ($isAnswer && $isChosen)
                                <span class="text-correct">✓ Your choice</span>
                            @elseif ($isAnswer)
                                <span class="text-correct">✓ Correct answer</span>
                            @elseif ($isChosen)
                                <span class="text-wrong">✗ Your choice</span>
                            @endif
                            <span class="block font-normal text-ink/60">{{ $choice }}</span>
                        </span>
                    </li>
                @endforeach
            </ul>

            <form method="POST" action="{{ route('quiz.next') }}" class="mt-6">
                @csrf
                <button type="submit" class="btn-primary w-full">
                    {{ $isLast ? 'See my score' : 'Next question' }}
                </button>
            </form>
        @endif
    </div>
@endsection

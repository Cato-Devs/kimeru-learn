@extends('layouts.app')

@section('title', 'Your quiz score')

@section('content')
    <div class="mx-auto max-w-2xl px-4 py-8 sm:py-12">
        <p class="text-sm font-medium uppercase tracking-widest text-ochre-dark">Quiz complete</p>
        <h1 class="mt-2 text-4xl font-semibold text-forest">You scored {{ $score }} / {{ $total }}</h1>
        <p class="mt-2 text-ink/70">
            @if ($score === $total)
                Excellent, every answer right!
            @elseif ($score >= 3)
                Well done. Look over the ones you missed below.
            @else
                Keep practising: the numbers get easier every round.
            @endif
        </p>

        <ol class="mt-8 space-y-3">
            @foreach ($questions as $q)
                @php $right = $q['chosen'] === $q['answer']; @endphp
                <li @class([
                    'rounded-xl border-l-4 bg-white p-4',
                    'border-correct' => $right,
                    'border-wrong' => ! $right,
                ])>
                    <p class="text-sm font-semibold">
                        <span @class(['text-correct' => $right, 'text-wrong' => ! $right])>
                            {{ $right ? '✓ Correct' : '✗ Incorrect' }}
                        </span>
                        <span class="font-normal text-ink/60">· Question {{ $loop->iteration }}</span>
                    </p>
                    <p class="mt-1 text-lg">
                        {{ $q['a'] }} {{ $q['operator'] }} {{ $q['b'] }} = {{ $q['answer'] }}
                    </p>
                    <p class="kimeru mt-1">
                        {{ $words[$q['a']] }} <span class="sign">{{ $q['operator'] }}</span>
                        {{ $words[$q['b']] }} <span class="sign">=</span>
                        {{ $words[$q['answer']] }}
                    </p>
                    @unless ($right)
                        <p class="mt-1 text-sm text-ink/70">
                            You chose {{ $q['chosen'] }}: <span class="kimeru">{{ $words[$q['chosen']] }}</span>
                        </p>
                    @endunless
                </li>
            @endforeach
        </ol>

        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <a href="{{ route('quiz') }}" class="btn-primary">Try another quiz</a>
            <a href="{{ route('numbers') }}" class="btn-secondary">Review the numbers</a>
        </div>
    </div>
@endsection

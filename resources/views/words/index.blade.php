@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-8 sm:py-12">
        <p class="text-sm"><a href="{{ route('words') }}" class="text-forest underline hover:no-underline">← All words</a></p>

        <div class="mt-3 flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold text-forest">{{ $category->name }}</h1>
                <p class="mt-1 text-ink/70">{{ $category->description }}</p>
            </div>

            @can('edit-words')
                <a href="{{ route('words.create', $category) }}" class="btn-primary">+ Add a word</a>
            @endcan
        </div>

        @include('partials.status')

        @if ($words->isEmpty())
            <p class="mt-8 rounded-xl border border-dashed border-forest/30 bg-white px-5 py-8 text-center text-ink/70">
                No words here yet.
                @can('edit-words')
                    Use <strong>Add a word</strong> to add the first one.
                @endcan
            </p>
        @else
            <ul class="mt-8 divide-y divide-forest/10 rounded-xl border border-forest/10 bg-white">
                @foreach ($words as $word)
                    <li class="flex flex-wrap items-start justify-between gap-4 px-5 py-4">
                        <div class="min-w-0">
                            {{-- {{ }} escapes HTML, so a word containing <script> is shown as text, never run. --}}
                            <p class="kimeru text-xl">{{ $word->kimeru }}</p>
                            <p class="mt-0.5">{{ $word->english }}</p>
                            @if ($word->notes)
                                <p class="mt-1 text-sm text-ink/60">{{ $word->notes }}</p>
                            @endif
                        </div>

                        @can('edit-words')
                            <div class="flex shrink-0 items-center gap-3 text-sm">
                                <a href="{{ route('words.edit', [$category, $word]) }}" class="font-semibold text-forest underline hover:no-underline">
                                    Edit<span class="sr-only"> {{ $word->kimeru }}</span>
                                </a>

                                {{-- HTML forms can only send GET or POST, so @method('DELETE')
                                     adds a hidden _method field that Laravel reads. --}}
                                <form method="POST" action="{{ route('words.destroy', [$category, $word]) }}"
                                      onsubmit="return confirm('Delete this word?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold text-wrong underline hover:no-underline">
                                        Delete<span class="sr-only"> {{ $word->kimeru }}</span>
                                    </button>
                                </form>
                            </div>
                        @endcan
                    </li>
                @endforeach
            </ul>
        @endif

        <p class="mt-6 text-sm text-ink/60">We are still checking these words with fluent Kimeru speakers.</p>
    </div>
@endsection

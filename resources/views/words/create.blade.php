@extends('layouts.app')

@section('title', 'Add a word')

@section('content')
    <div class="mx-auto max-w-xl px-4 py-8 sm:py-12">
        <p class="text-sm"><a href="{{ route('words.index', $category) }}" class="text-forest underline hover:no-underline">← {{ $category->name }}</a></p>
        <h1 class="mt-3 text-3xl font-semibold text-forest">Add a word to {{ $category->name }}</h1>

        <form method="POST" action="{{ route('words.store', $category) }}" class="mt-8">
            @csrf
            @include('words._form')

            <div class="mt-8 flex flex-wrap gap-3">
                <button type="submit" class="btn-primary">Add word</button>
                <a href="{{ route('words.index', $category) }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection

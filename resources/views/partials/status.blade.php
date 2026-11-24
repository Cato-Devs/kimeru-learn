{{-- A one-off message from the server after a redirect, e.g. "Added "Muuga"." --}}
@if (session('status'))
    <p role="status" class="mt-6 rounded-lg bg-correct/10 px-4 py-3 text-sm font-medium text-correct">
        {{ session('status') }}
    </p>
@endif

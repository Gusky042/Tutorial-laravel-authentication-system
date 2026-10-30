{{-- Error pages can render without a session (e.g. a 404 before the session starts). --}}
@if (request()->hasSession())
    @if (session('status'))
        <div role="status" class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div role="alert" class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif
@endif

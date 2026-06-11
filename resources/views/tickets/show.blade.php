@extends(in_array(auth()->user()->role, ['admin', 'seller']) ? 'layouts.app' : 'layouts.user')

@section('sidebar')
    @include('user.sidebar')
@endsection

@section('content')
    <div class="container py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="mb-1">
                    {{ $ticket->subject }}
                </h3>

                <small class="text-muted">
                    Ticket #TKT-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                </small>
            </div>


            <!-- STATUS DROPDOWN -->

            @if (auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('admin.tickets.status.update', $ticket->id) }}">

                    @csrf
                    @method('PATCH')

                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">

                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>
                            Open
                        </option>

                        <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>
                            Closed
                        </option>

                    </select>
                </form>
            @else
                @if ($ticket->status == 'open')
                    <span class="badge bg-success">Open</span>
                @elseif($ticket->status == 'pending')
                    <span class="badge bg-warning text-dark">Pending</span>
                @else
                    <span class="badge bg-secondary">Closed</span>
                @endif
            @endif

        </div>

        <!-- Ticket Details -->
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-light">
                <h5 class="mb-0">
                    Ticket Details
                </h5>
            </div>

            <div class="card-body">

                <div class="row mb-3">

                    <div class="col-md-4">
                        <strong>Category:</strong><br>
                        {{ optional($ticket->category)->name ?? '-' }}
                    </div>

                    <div class="col-md-4">
                        <strong>Priority:</strong><br>

                        @if ($ticket->priority == 'high')
                            <span class="badge bg-danger">
                                High
                            </span>
                        @elseif($ticket->priority == 'medium')
                            <span class="badge bg-warning text-dark">
                                Medium
                            </span>
                        @else
                            <span class="badge bg-success">
                                Low
                            </span>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <strong>Created:</strong><br>
                        {{ $ticket->created_at->format('d M Y h:i A') }}
                    </div>

                </div>

                <hr>

                <h6>Message</h6>

                <p class="mb-0">
                    {{ $ticket->message }}
                </p>

            </div>

        </div>

        <!-- Attachment -->
        @if ($ticket->attachment)
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-light">
                    Attachment
                </div>

                <div class="card-body">

                    <img src="{{ asset('storage/' . $ticket->attachment) }}" class="img-fluid rounded border"
                        style="max-height:300px;">

                    <div class="mt-2">

                        <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank"
                            class="btn btn-sm btn-outline-primary">
                            View Full Image
                        </a>

                    </div>

                </div>

            </div>
        @endif

        <!-- Conversation -->
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-light">
                Conversation
            </div>

            <div class="card-body">

                @forelse($ticket->replies as $reply)
                    <div class="mb-3">

                        <div
                            class="p-3 rounded
                        {{ $reply->is_admin ? 'bg-light border' : 'bg-primary text-white' }}">

                            <div class="d-flex justify-content-between">

                                <strong>
                                    {{ $reply->is_admin ? 'Support Team' : $reply->user->name ?? 'Customer' }}
                                </strong>

                                <small>
                                    {{ $reply->created_at->format('d M Y h:i A') }}
                                </small>

                            </div>

                            <hr class="{{ $reply->is_admin ? '' : 'border-light' }}">

                            <p class="mb-0">
                                {{ $reply->message }}
                            </p>

                        </div>

                    </div>

                @empty

                    <div class="text-center text-muted py-3">
                        No replies yet.
                    </div>
                @endforelse

            </div>

        </div>

        <!-- Reply Form -->
        <div class="card shadow-sm border-0">

            <div class="card-header bg-light">
                Add Reply
            </div>

            <div class="card-body">

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                {{-- @if ($ticket->status !== 'closed') --}}

                <form method="POST" action="{{ route('tickets.reply', $ticket->id) }}">

                    @csrf

                    <div class="mb-3">

                        <textarea name="message" rows="4" class="form-control @error('message') is-invalid @enderror"
                            placeholder="Write your reply here...">{{ old('message') }}</textarea>

                        @error('message')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <button type="submit" class="btn btn-success">
                        Send Reply
                    </button>

                </form>

                {{-- @else

                <div class="alert alert-warning mb-0">
                    This ticket is closed. No further replies can be added.
                </div>

                @endif --}}

            </div>

        </div>

        <!-- Back Button -->
        <div class="mt-4">

            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.tickets') }}" class="btn btn-secondary">
                    Back to Tickets
                </a>
            @else
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                    Back to My Tickets
                </a>
            @endif

        </div>

    </div>
@endsection

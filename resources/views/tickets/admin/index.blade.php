@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>All Support Tickets (Admin)</h3>
        </div>

        <div class="card shadow-sm">

            <div class="card-body">

                <!-- SEARCH -->
                <form method="GET" class="row g-3 mb-4">

                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Search subject or user..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">
                            Search
                        </button>
                    </div>

                </form>

                <!-- TABLE -->
                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Subject</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Status Control</th>
                                <th width="220">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($tickets as $ticket)
                                <tr>

                                    <td>{{ $ticket->id }}</td>

                                    <td>
                                        {{ $ticket->user->name ?? '-' }}
                                    </td>

                                    <td>
                                        <strong>{{ $ticket->subject }}</strong>
                                    </td>

                                    <td>
                                        {{ optional($ticket->category)->name ?? '-' }}
                                    </td>

                                    <!-- PRIORITY -->
                                    <td>
                                        @if ($ticket->priority == 'high')
                                            <span class="badge bg-danger">High</span>
                                        @elseif($ticket->priority == 'medium')
                                            <span class="badge bg-warning text-dark">Medium</span>
                                        @else
                                            <span class="badge bg-success">Low</span>
                                        @endif
                                    </td>

                                    <!-- STATUS BADGE -->
                                    <td>
                                        @if ($ticket->status == 'open')
                                            <span class="badge bg-success">Open</span>
                                        @elseif($ticket->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-secondary">Closed</span>
                                        @endif
                                    </td>

                                    <!-- STATUS DROPDOWN -->
                                    <td>

                                        <form method="POST"
                                            action="{{ route('admin.tickets.status.update', $ticket->id) }}">

                                            @csrf
                                            @method('PATCH')

                                            <select name="status" class="form-select form-select-sm"
                                                onchange="this.form.submit()">

                                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>
                                                    Open
                                                </option>

                                                <option value="pending"
                                                    {{ $ticket->status == 'pending' ? 'selected' : '' }}>
                                                    Pending
                                                </option>

                                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>
                                                    Closed
                                                </option>

                                            </select>

                                        </form>

                                    </td>

                                    <!-- ACTIONS -->
                                    <td class="d-flex gap-1 flex-wrap">

                                        <!-- VIEW -->
                                        <a href="{{ route('tickets.show', $ticket->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>

                                        <!-- CLOSE -->
                                        @if ($ticket->status != 'closed')
                                            <form action="{{ route('tickets.close', $ticket->id) }}" method="POST">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-sm btn-warning"
                                                    onclick="return confirm('Close this ticket?')">
                                                    Close
                                                </button>

                                            </form>
                                        @endif

                                        <!-- DELETE -->
                                        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this ticket permanently?')">
                                                Delete
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        No tickets found.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                <!-- PAGINATION -->
                <div class="mt-3">
                    {{ $tickets->withQueryString()->links() }}
                </div>

            </div>
        </div>

    </div>
@endsection

@extends(
    in_array(auth()->user()->role, ['admin', 'seller'])
        ? 'layouts.app'
        : 'layouts.user'
)
@section('sidebar')
    @include('user.sidebar')
@endsection

@section('content')
    <div class="container py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="mb-1">My Tickets</h3>
                <p class="text-muted mb-0">Track and manage your support requests</p>
            </div>

            <a href="{{ route('tickets.create') }}" class="btn btn-success">
                + Create Ticket
            </a>

        </div>
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

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>Ticket ID</th>
                                <th>Subject</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($tickets as $ticket)
                                <tr>

                                    <td>
                                        <strong>
                                            TKT-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $ticket->subject }}
                                    </td>

                                    <td>
                                        {{ optional($ticket->category)->name ?? '-' }}
                                    </td>

                                    <td>
                                        @if ($ticket->priority == 'high')
                                            <span class="badge bg-danger">High</span>
                                        @elseif($ticket->priority == 'medium')
                                            <span class="badge bg-warning text-dark">Medium</span>
                                        @else
                                            <span class="badge bg-success">Low</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($ticket->status == 'open')
                                            <span class="badge bg-success">Open</span>
                                        @elseif ($ticket->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-secondary">Closed</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $ticket->created_at->format('d M Y') }}
                                    </td>

                                    <td>
                                        {{ $ticket->updated_at->format('d M Y') }}
                                    </td>

                                    <td>
                                        <a href="{{ route('tickets.show', $ticket->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="8" class="text-center py-5">

                                        <h5 class="text-muted">No tickets found</h5>

                                        <p class="text-muted">Create your first support ticket</p>

                                        <a href="{{ route('tickets.create') }}" class="btn btn-success">
                                            Create Ticket
                                        </a>

                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection

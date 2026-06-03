@extends('layouts.app')

@section('content')
    <div class="container">

        <h2>All Support Tickets (Admin)</h2>

        <table class="table table-bordered">

            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            @foreach ($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>

                    <td>
                        {{ $ticket->user->name }}
                    </td>

                    <td>{{ $ticket->subject }}</td>

                    <td>
                        <span class="badge bg-primary">
                            {{ $ticket->status }}
                        </span>
                    </td>

                    <td>
                        <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-info">
                            View
                        </a>
                        
                        @if ($ticket->status !== 'closed')
                            <form action="{{ route('tickets.close', $ticket->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Close this ticket?')">
                                    Close
                                </button>
                            </form>
                        @else
                            <span class="badge bg-secondary">Closed</span>
                        @endif
                    </td>
                </tr>
            @endforeach

        </table>

    </div>
@endsection

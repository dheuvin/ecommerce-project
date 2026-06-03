@extends('layouts.user')

@section('content')
<div class="container">

    <h2>My Tickets</h2>

    <a href="{{ route('tickets.create') }}"
       class="btn btn-success mb-3">
        Create Ticket
    </a>

    <table class="table table-bordered">

        <tr>
            <th>ID</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        @foreach($tickets as $ticket)
        <tr>
            <td>{{ $ticket->id }}</td>
            <td>{{ $ticket->subject }}</td>
            <td>{{ $ticket->status }}</td>
            <td>
                <a href="{{ route('tickets.show',$ticket->id) }}"
                   class="btn btn-info btn-sm">
                    View
                </a>
            </td>
        </tr>
        @endforeach

    </table>

</div>
@endsection

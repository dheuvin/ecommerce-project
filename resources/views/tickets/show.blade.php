@extends('layouts.user')

@section('content')
    <div class="container">

        <h3>{{ $ticket->subject }}</h3>

        <div class="card mb-3">
            <div class="card-body">
                {{ $ticket->message }}
            </div>
        </div>

        @if ($ticket->attachment)
            <div class="mb-3">
                <img src="{{ asset('storage/' . $ticket->attachment) }}" class="img-fluid" style="max-width:300px;">
            </div>
        @endif

        @foreach ($ticket->replies as $reply)
            <div class="card mb-2">
                <div class="card-body">
                    <b>{{ $reply->is_admin ? 'Admin' : 'You' }}</b>
                    <p>{{ $reply->message }}</p>
                </div>
            </div>
        @endforeach

        <form method="POST" action="{{ route('tickets.reply', $ticket->id) }}">
            @csrf

            <textarea name="message" class="form-control mb-2" placeholder="Type reply..."></textarea>

            <button class="btn btn-success">
                Reply
            </button>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.tickets') }}" class="btn btn-secondary">
                    Back
                </a>
            @else
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                    Back
                </a>
            @endif
        </form>

        <br>



    </div>
@endsection

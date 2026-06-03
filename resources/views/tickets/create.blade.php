@extends('layouts.user')

@section('content')
    <div class="container">

        <h2>Create Ticket</h2>

        <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Subject</label>
                <input type="text" name="subject" class="form-control">
            </div>

            <div class="mb-3">
                <label>Message</label>
                <textarea name="message" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Screenshot (Optional)</label>

                <input type="file" name="attachment" class="form-control">
            </div>

            <button class="btn btn-primary">
                Submit
            </button>

        </form>

    </div>
@endsection

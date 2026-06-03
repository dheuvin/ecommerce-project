@extends(
    auth()->check() && auth()->user()->role == 'admin'
        ? 'layouts.app'
        : 'layouts.user'
)

@section('content')

<div class="container py-5">

    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">
        ← Back
    </a>

    <div class="card shadow">
        <div class="card-body">

            <h1>{{ $blog->title }}</h1>

            <hr>

            <p>
                {{ $blog->content }}
            </p>

        </div>
    </div>

</div>

@endsection

@extends(
    auth()->check() && auth()->user()->role == 'admin'
        ? 'layouts.app'
        : 'layouts.user'
)

@section('content')

@if(auth()->check() && auth()->user()->role == 'admin')

    {{-- ADMIN VIEW --}}

    <div class="container mt-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Blog List</h1>

            <a href="{{ route('blog.create') }}" class="btn btn-primary">
                + Create Blog
            </a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($blogs as $blog)
                <tr>
                    <td>{{ $blog->id }}</td>
                    <td>{{ $blog->title }}</td>
                    <td>{{ Str::limit($blog->content, 50) }}</td>

                    <td>
                        <a href="{{ route('blog.show',$blog->id) }}"
                           class="btn btn-info btn-sm">
                            View
                        </a>

                        <a href="{{ route('blog.edit',$blog->id) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('blog.destroy',$blog->id) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
        <div class="d-flex justify-content-center mt-3">
    {{ $blogs->links() }}
</div>

    </div>

@else

    {{-- USER VIEW --}}

    <div class="container py-5">

        <div class="text-center mb-5">
            <h1>All Blogs</h1>
            <p class="text-muted">Read our latest articles and updates</p>
        </div>

        <div class="row">

            @foreach($blogs as $blog)

            <div class="col-md-4 mb-4">
                <div class="card shadow h-100">

                    <div class="card-body">
                        <h4>{{ $blog->title }}</h4>

                        <p>
                            {{ \Illuminate\Support\Str::limit($blog->content, 120) }}
                        </p>
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('blog.show', $blog->id) }}"
                           class="btn btn-primary">
                            Read More
                        </a>
                    </div>

                </div>
            </div>

            @endforeach

        </div>

    </div>

@endif

@endsection

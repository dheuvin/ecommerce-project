@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">Create Blog</h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('blog.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Enter blog title"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea name="content" class="form-control" rows="5" placeholder="Enter blog content" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-success">
                                Save Blog
                            </button>

                            <a href="{{ route('blog.index') }}" class="btn btn-secondary">
                                Back
                            </a>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

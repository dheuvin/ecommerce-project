@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Update Blog</h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('blog.update', $blog->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $blog->title }}"
                                    placeholder="Enter title">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea name="content" class="form-control" rows="5" placeholder="Enter content">{{ $blog->content }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-success">
                                Update Blog
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

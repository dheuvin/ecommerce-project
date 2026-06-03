@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Categories</h3>

    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        + Create Category
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover table-bordered mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Parent Category</th>
                    <th>Status</th>
                    <th style="width: 180px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td>{{ $category->parent?->name ?? 'Main Category' }}</td>
                        <td>
                            @if ($category->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form
                                action="{{ route('categories.destroy', $category) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this category?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            No categories found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

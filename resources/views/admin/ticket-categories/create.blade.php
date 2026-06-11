@extends('layouts.app')

@section('content')

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <div class="card border-0 shadow">

                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            Create Ticket Category
                        </h4>
                    </div>

                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Please fix the following errors:</strong>

                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('ticket-categories.store') }}" method="POST">

                            @csrf

                            <div class="mb-4">

                                <label for="name" class="form-label fw-semibold">
                                    Category Name
                                    <span class="text-danger">*</span>
                                </label>


                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter category name">

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">

                                <a href="{{ route('ticket-categories.index') }}" class="btn btn-outline-secondary">
                                    Back
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    Save Category
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>
        </div>


    </div>
@endsection

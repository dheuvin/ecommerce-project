@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Manage Users</h3>

            <!-- Search Form -->
            <form method="GET" action="" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search name or email"
                    value="{{ request('search') }}">

                <button class="btn btn-primary">Search</button>
            </form>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th width="200">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>

                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ $user->role }}
                                    </span>
                                </td>

                                <td>
                                    <form method="POST" action="{{ url('/admin/users/' . $user->id . '/role') }}"
                                        class="d-flex gap-2">

                                        @csrf

                                        <select name="role" class="form-select form-select-sm">
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                                                Admin
                                            </option>
                                            <option value="seller" {{ $user->role == 'seller' ? 'selected' : '' }}>
                                                Seller
                                            </option>
                                            <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>
                                                Customer
                                            </option>

                                        </select>

                                        <button class="btn btn-success btn-sm">
                                            Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
                <div class="d-flex justify-content-center mt-3">
                    {{ $users->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection

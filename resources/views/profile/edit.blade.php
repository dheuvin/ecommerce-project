@extends(
    in_array(auth()->user()->role, ['admin', 'seller'])
        ? 'layouts.app'
        : 'layouts.user'
)

@section('sidebar')
    @include('user.sidebar')
@endsection

@section('content')
<div class="container py-4">

    <h3>My Profile</h3>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <!-- NAME -->
        <div class="mb-3">
            <label>Name</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ $user->name }}">
        </div>

        <!-- EMAIL (READ ONLY) -->
        <div class="mb-3">
            <label>Email</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   value="{{ $user->email }}">
        </div>

        <!-- BIRTHDAY -->
        <div class="mb-3">
            <label>Birthday</label>
            <input type="date"
                   name="birthday"
                   class="form-control"
                   value="{{ $user->birthday }}">
        </div>

        <!-- PASSWORD -->
        <div class="mb-3">
            <label>New Password (optional)</label>
            <input type="password"
                   name="password"
                   class="form-control">
        </div>

        <!-- CONFIRM PASSWORD -->
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password"
                   name="password_confirmation"
                   class="form-control">
        </div>

        <button class="btn btn-primary">
            Update Profile
        </button>

    </form>

</div>
@endsection

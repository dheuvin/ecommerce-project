<nav class="navbar navbar-dark shadow-sm"
     style="background: black;">

    <div class="container d-flex justify-content-between align-items-center">

        {{-- Left: Dashboard --}}
        <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
            Admin Dashboard
        </a>

        {{-- Right: Logout --}}
        <form action="{{ route('logout') }}" method="POST" class="mb-0">
            @csrf
            <button class="btn btn-light btn-sm">
                Logout
            </button>
        </form>

    </div>
</nav>

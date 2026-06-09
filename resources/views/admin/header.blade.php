<nav class="navbar navbar-dark px-4" style="background:#111827">

    <div class="container-fluid">

        <h4 class="text-white mb-0">
            <i class="bi bi-speedometer2"></i>
            Admin Dashboard
        </h4>

        <div class="d-flex align-items-center gap-3">

            <span class="text-white">
                Welcome,
                <strong>{{ auth()->user()->name }}</strong>
            </span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-danger">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>

        </div>

    </div>
</nav>

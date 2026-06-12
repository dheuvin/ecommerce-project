@extends('layouts.user')

@section('content')
    @php
        $lockSeconds = session('lock_seconds', 0);
    @endphp
    <div class="container">

        <div class="row justify-content-center align-items-center vh-100">

            <div class="col-md-5">

                <div class="card border-0 shadow-lg">

                    <div class="card-body p-5">

                        <div class="text-center mb-4">

                            <h1 class="fw-bold">
                                Login
                            </h1>

                            <p class="text-muted">
                                Login to your account
                            </p>

                        </div>

                        {{-- @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif --}}

                        <form action="/login" method="POST">

                            @csrf

                            {{-- EMAIL --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Email Address
                                </label>

                                <input type="email" name="email" class="form-control form-control-lg"
                                    placeholder="Enter your email" required>

                            </div>

                            {{-- PASSWORD --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Password
                                </label>

                                <input type="password" name="password" class="form-control form-control-lg"
                                    placeholder="Enter your password" required>

                            </div>

                            {{-- BUTTON --}}
                            @if ($lockSeconds > 0)
                                <div class="alert alert-danger text-center">
                                    Account locked. Try again after {{ gmdate('H:i:s', $lockSeconds) }}
                                </div>
                            @else
                                <div class="d-grid">
                                    <button id="loginBtn" type="submit" class="btn btn-dark btn-lg">
                                        Login
                                    </button>
                                </div>
                            @endif

                        </form>

                        {{-- REGISTER --}}
                        <div class="text-center mt-4">

                            <p class="text-muted mb-0">

                                Don't have an account?

                                <a href="{{ route('register') }}" class="text-dark fw-bold text-decoration-none">

                                    Register

                                </a>

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <script>
        let lockSeconds = {{ session('lock_seconds', 0) }};
        let btn = document.getElementById("loginBtn");

        if (lockSeconds > 0 && btn) {

            btn.disabled = true;

            let timer = setInterval(() => {

                lockSeconds--;

                btn.innerText = "Try again in " + lockSeconds + "s";

                if (lockSeconds <= 0) {
                    clearInterval(timer);
                    btn.disabled = false;
                    btn.innerText = "Login";
                }

            }, 1000);
        }
    </script>
@endsection

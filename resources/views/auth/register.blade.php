@extends('layouts.user')

@section('content')

    <div class="container">

        <div class="row justify-content-center align-items-center vh-100">

            <div class="col-md-5">

                <div class="card border-0 shadow-lg">

                    <div class="card-body p-5">

                        <div class="text-center mb-4">

                            <h1 class="fw-bold">
                                Register
                            </h1>

                            <p class="text-muted">
                                Create your account
                            </p>

                        </div>

                        {{-- ERRORS --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">

                                <ul class="mb-0">

                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach

                                </ul>

                            </div>
                        @endif

                        <form action="/register" method="POST">

                            @csrf

                            {{-- NAME --}}
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Full Name
                                </label>

                                <input type="text" name="name" class="form-control form-control-lg"
                                    placeholder="Enter your name" required>

                            </div>

                            {{-- birthday --}}
                            <div class="mt-3">
                                <label for="birthday">Birthday</label>
                                <input type="date" name="birthday" class="form-control" required>
                            </div>

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
                            <div class="d-grid">

                                <button type="submit" class="btn btn-dark btn-lg">

                                    Register

                                </button>

                            </div>

                        </form>

                        {{-- LOGIN --}}
                        <div class="text-center mt-4">

                            <p class="text-muted mb-0">

                                Already have an account?

                                <a href="{{ route('login') }}" class="text-dark fw-bold text-decoration-none">

                                    Login

                                </a>

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection

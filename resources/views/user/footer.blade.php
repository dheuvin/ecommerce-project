<footer class="mt-auto border-top bg-white py-5">
    <div class="container">

        <div class="row g-4 align-items-start">

            <!-- Left Section -->
            <div class="col-md-6">
                <a class="brand-mark d-flex align-items-center mb-2 text-decoration-none"
                   href="{{ route('shop.index') }}">

                    <span class="brand-mark-icon me-2">
                        <i class="bi bi-bag-check"></i>
                    </span>

                    <strong>Ecommerce</strong>
                </a>

                <p class="text-muted mb-0 small">
                    Premium marketplace shopping with verified sellers and secure checkout.
                </p>
            </div>

            <!-- Right Section (Links) -->
            <div class="col-md-6 text-md-end">

                <a class="fw-semibold text-decoration-none d-block mb-2"
                   href="{{ route('blog.index') }}">
                    Blogs
                </a>

                @auth
                    @if (auth()->user()->isCustomer())
                        <a href="{{ route('tickets.index') }}"
                           class="btn btn-outline-dark btn-sm mb-3 mb-md-0">
                            <i class="bi bi-life-preserver me-1"></i> Support
                        </a>
                    @endif
                @endauth

            </div>

        </div>

        <hr class="my-4">

        <!-- Bottom Row -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">

            <span class="text-muted small mb-2 mb-md-0">
                © 2026 Ecommerce Store. All rights reserved.
            </span>

        </div>

    </div>
</footer>

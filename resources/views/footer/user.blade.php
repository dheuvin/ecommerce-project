<footer class="mt-auto border-top bg-white py-5">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-md-6">
                <a class="brand-mark mb-2" href="{{ route('shop.index') }}">
                    <span class="brand-mark-icon"><i class="bi bi-bag-check"></i></span>
                    Ecommerce
                </a>
                <p class="text-muted mb-0 small">Premium marketplace shopping with verified sellers and secure checkout.</p>
            </div>

            <div class="col-md-6 text-md-end">
                @auth
                    @if(auth()->user()->isCustomer())
                        <a href="{{ route('tickets.index') }}" class="btn btn-outline-dark btn-premium mb-3 mb-md-0 me-md-2">
                            <i class="bi bi-life-preserver me-1"></i> Support
                        </a>
                    @endif
                @endauth

                <span class="text-muted small d-inline-block">Copyright 2026 Ecommerce Store. All rights reserved.</span>
            </div>
        </div>
    </div>
</footer>

<footer class="bg-dark text-white py-4 mt-auto">
    <div class="container text-center">

        @auth
            @if(auth()->user()->isCustomer())
                <a href="{{ route('tickets.index') }}"
                   class="btn btn-outline-light mb-3">
                    Support Ticket
                </a>
            @endif
        @endauth

        <div>
            © 2026 Ecommerce Store — All Rights Reserved.
        </div>

    </div>
</footer>

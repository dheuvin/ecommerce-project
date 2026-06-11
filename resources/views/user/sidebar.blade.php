<div class="bg-light min-vh-100 border-end p-3">
    <h5 class="mb-4 text-dark">Menu</h5>

    <ul class="nav flex-column">

        <li class="nav-item mb-2">
            <a href="{{ route('blog.index') }}" class="nav-link text-dark">
                <i class="bi bi-journal-text me-2"></i>
                Blog
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('tickets.index') }}" class="nav-link text-dark">
                <i class="bi bi-ticket-detailed me-2"></i>
                Tickets
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('profile.edit') }}" class="nav-link text-dark">
                <i class="bi bi-person-circle me-2"></i>
                Profile
            </a>
        </li>

    </ul>
</div>

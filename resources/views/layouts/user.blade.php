<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Ecommerce')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --brand-ink: #111827;
            --brand-muted: #6b7280;
            --brand-line: #e5e7eb;
            --brand-soft: #f7f8fb;
            --brand-accent: #0f766e;
            --brand-warm: #f59e0b;
            --brand-radius: 18px;
            --brand-shadow: 0 18px 45px rgba(17, 24, 39, .08);
        }

        * {
            letter-spacing: 0;
        }

        body {
            background: #fff;
            color: var(--brand-ink);
            font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            text-rendering: optimizeLegibility;
        }

        .site-header {
            backdrop-filter: blur(18px);
            background: rgba(255, 255, 255, .92);
            border-bottom: 1px solid rgba(229, 231, 235, .9);
        }

        .brand-mark {
            align-items: center;
            color: var(--brand-ink);
            display: inline-flex;
            font-size: 1.35rem;
            font-weight: 800;
            gap: .65rem;
            text-decoration: none;
        }

        .brand-mark-icon {
            align-items: center;
            background: var(--brand-ink);
            border-radius: 12px;
            color: #fff;
            display: inline-flex;
            height: 38px;
            justify-content: center;
            width: 38px;
        }

        .nav-search {
            background: var(--brand-soft);
            border: 1px solid var(--brand-line);
            border-radius: 999px;
            min-width: min(360px, 100%);
        }

        .nav-search .form-control {
            background: transparent;
            border: 0;
            box-shadow: none;
            font-size: .92rem;
        }

        .nav-link,
        .dropdown-item {
            font-size: .92rem;
            font-weight: 600;
        }

        .btn-premium,
        .btn-icon {
            border-radius: 999px;
            font-weight: 700;
            transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease, border-color .18s ease;
        }

        .btn-premium:hover,
        .btn-icon:hover,
        .product-card:hover {
            transform: translateY(-2px);
        }

        .btn-dark {
            background: var(--brand-ink);
            border-color: var(--brand-ink);
        }

        .btn-icon {
            align-items: center;
            display: inline-flex;
            height: 40px;
            justify-content: center;
            padding: 0;
            position: relative;
            width: 40px;
        }

        .count-badge {
            border: 2px solid #fff;
            font-size: .65rem;
            min-width: 21px;
            position: absolute;
            right: -7px;
            top: -8px;
        }

        .section-pad {
            padding: 64px 0;
        }

        .eyebrow {
            color: var(--brand-accent);
            font-size: .76rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .surface {
            background: #fff;
            border: 1px solid var(--brand-line);
            border-radius: var(--brand-radius);
            box-shadow: var(--brand-shadow);
        }

        .soft-surface {
            background: var(--brand-soft);
            border: 1px solid var(--brand-line);
            border-radius: var(--brand-radius);
        }

        .product-card {
            border: 1px solid var(--brand-line);
            border-radius: var(--brand-radius);
            box-shadow: 0 10px 28px rgba(17, 24, 39, .05);
            overflow: hidden;
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .product-card:hover {
            box-shadow: var(--brand-shadow);
        }

        .product-media {
            aspect-ratio: 1 / 1.08;
            background: var(--brand-soft);
            overflow: hidden;
            position: relative;
        }

        .product-media img {
            height: 100%;
            object-fit: cover;
            transition: transform .45s ease;
            width: 100%;
        }

        .product-card:hover .product-media img {
            transform: scale(1.04);
        }

        .floating-action {
            position: absolute;
            right: 12px;
            top: 12px;
            z-index: 2;
        }

        .rating-stars {
            color: var(--brand-warm);
            font-size: .9rem;
            letter-spacing: .02em;
        }

        .price {
            color: var(--brand-ink);
            font-weight: 800;
        }

        .form-control,
        .form-select {
            border-color: var(--brand-line);
            border-radius: 14px;
            min-height: 46px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand-ink);
            box-shadow: 0 0 0 .2rem rgba(17, 24, 39, .08);
        }

        .alert {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(17, 24, 39, .06);
        }

        @media (max-width: 991.98px) {
            .nav-search {
                margin: 12px 0;
                width: 100%;
            }

            .section-pad {
                padding: 40px 0;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
     @include('user.header')

    <div class="container mt-4">
        <div id="ajax-feedback">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <main class="flex-grow-1">
        @yield('content')
    </main>

    @include('user.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

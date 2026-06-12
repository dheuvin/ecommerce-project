    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Ecommerce')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
            rel="stylesheet">
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

            .category-wrapper {
                overflow: hidden;
            }

            .category-scroll {
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
                scroll-behavior: smooth;
            }

            /* 👉 5 items visible */
            .category-item {
                flex: 0 0 20%;
                /* 100% / 5 = 20% */
                scroll-snap-align: start;
            }

            /* card styling */
            .category-card {
                width: 100%;
            }

            /* hide scrollbar */
            .category-scroll::-webkit-scrollbar {
                display: none;
            }

            .category-bar {
                white-space: nowrap;
            }

            .parent-scroll {
                overflow-x: auto;
                white-space: nowrap;
                scrollbar-width: none;
            }

            .parent-scroll::-webkit-scrollbar {
                display: none;
            }

            .parent-category {
                flex-shrink: 0;
            }

            .card {
                border-radius: 15px;
            }

            .form-control {
                border-radius: 10px;
            }

            .list-group-item {
                background: transparent;
                font-size: 15px;
            }

            .btn {
                border-radius: 10px;
                font-weight: 500;
            }

            .sidebar-filter {
                position: sticky;
                top: 20px;
            }

            .form-check {
                font-size: 14px;
            }

            .size-box {
                cursor: pointer;
                min-width: 70px;
                transition: 0.2s;
            }

            /* Styling modern selection states */
            .size-box {
                cursor: pointer;
                transition: all 0.2s ease-in-out;
            }

            .size-box:has(input[type="radio"]:checked) {
                border-color: #000000 !important;
                background-color: #f8f9fa;
                box-shadow: 0 0 0 2px #000000;
            }

            .size-box:has(input[type="radio"]:disabled) {
                cursor: not-allowed;
                border-style: dashed;
            }

            .transition-all {
                transition: all 0.2s ease-in-out;
            }

            .form-check-label {
                cursor: pointer;
            }

            .catalog-loading {
                align-items: center;
                background: rgba(255, 255, 255, .72);
                border-radius: var(--brand-radius);
                bottom: 0;
                display: flex;
                justify-content: center;
                left: 0;
                min-height: 220px;
                position: absolute;
                right: 0;
                top: 0;
                z-index: 5;
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


        <div id="ajax-feedback">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show js-auto-dismiss-alert" role="alert">
                    {{ session('success') }}
                </div>
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


        {{-- <div class="container-fluid px-2 mt-3"> --}}
        <div>
            <div class="row">

                @hasSection('sidebar')
                    <div class="col-md-3">
                        @yield('sidebar')
                    </div>

                    <div class="col-md-9">
                        @yield('content')
                    </div>
                @else
                    <div class="col-12">
                        @yield('content')
                    </div>
                @endif

            </div>
        </div>

        @include('user.footer')

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.js-auto-dismiss-alert').forEach(function(alert) {
                    setTimeout(function() {
                        bootstrap.Alert.getOrCreateInstance(alert).close();
                    }, 5000);
                });

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const feedback = document.getElementById('ajax-feedback');
                const catalogResults = document.getElementById('catalog-results');
                const catalogLoading = document.getElementById('catalog-loading');
                const searchForm = document.querySelector('[data-catalog-search]');
                const filterForm = document.querySelector('[data-catalog-filter]');
                let catalogTimer = null;
                let catalogController = null;

                function showFeedback(message, type = 'success') {
                    if (!feedback || !message) {
                        return;
                    }

                    feedback.innerHTML = `
                        <div class="alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show js-auto-dismiss-alert" role="alert">
                            ${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;

                    const alert = feedback.querySelector('.alert');
                    setTimeout(function() {
                        if (alert) {
                            bootstrap.Alert.getOrCreateInstance(alert).close();
                        }
                    }, 3500);
                }

                function updateCount(selector, value) {
                    document.querySelectorAll(selector).forEach(function(badge) {
                        badge.textContent = value ?? 0;
                    });
                }

                function currentCatalogPath() {
                    return filterForm?.getAttribute('action') || window.location.href;
                }

                function setCatalogScope(url) {
                    if (!filterForm) {
                        return;
                    }

                    const scopedUrl = new URL(url, window.location.origin);
                    const activeSearch = scopedUrl.searchParams.get('search') || '';
                    let hiddenSearch = filterForm.querySelector('input[type="hidden"][name="search"]');

                    filterForm.setAttribute('action', scopedUrl.toString());

                    if (activeSearch) {
                        if (!hiddenSearch) {
                            hiddenSearch = document.createElement('input');
                            hiddenSearch.type = 'hidden';
                            hiddenSearch.name = 'search';
                            filterForm.appendChild(hiddenSearch);
                        }

                        hiddenSearch.value = activeSearch;
                    } else {
                        hiddenSearch?.remove();
                    }
                }

                function catalogUrlFromForm(form) {
                    const url = new URL(form.getAttribute('action') || window.location.href, window.location.origin);
                    const params = new URLSearchParams(url.search);
                    const formData = new FormData(form);

                    for (const [key, value] of formData.entries()) {
                        if (value === '') {
                            params.delete(key);
                        } else {
                            params.set(key, value);
                        }
                    }

                    url.search = params.toString();
                    return url;
                }

                async function loadCatalog(url, pushState = true) {
                    if (!catalogResults) {
                        window.location.href = url;
                        return;
                    }

                    if (catalogController) {
                        catalogController.abort();
                    }

                    catalogController = new AbortController();
                    catalogLoading?.classList.remove('d-none');

                    try {
                        const response = await fetch(url, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            signal: catalogController.signal,
                        });

                        if (!response.ok) {
                            throw new Error('Unable to load products.');
                        }

                        const data = await response.json();
                        catalogResults.innerHTML = data.html;
                        setCatalogScope(url);

                        if (pushState) {
                            window.history.pushState({}, '', url);
                        }
                    } catch (error) {
                        if (error.name !== 'AbortError') {
                            showFeedback(error.message, 'error');
                        }
                    } finally {
                        catalogLoading?.classList.add('d-none');
                    }
                }

                function debouncedCatalogLoad(url) {
                    clearTimeout(catalogTimer);
                    catalogTimer = setTimeout(function() {
                        loadCatalog(url);
                    }, 400);
                }

                if (searchForm) {
                    const searchInput = searchForm.querySelector('input[name="search"]');

                    searchForm.addEventListener('submit', function(event) {
                        if (!catalogResults) {
                            return;
                        }

                        event.preventDefault();
                        setCatalogScope(searchForm.action);
                        loadCatalog(catalogUrlFromForm(searchForm));
                    });

                    searchInput?.addEventListener('input', function() {
                        if (!catalogResults) {
                            return;
                        }

                        setCatalogScope(searchForm.action);
                        debouncedCatalogLoad(catalogUrlFromForm(searchForm));
                    });
                }

                filterForm?.addEventListener('submit', function(event) {
                    if (!catalogResults) {
                        return;
                    }

                    event.preventDefault();
                    loadCatalog(catalogUrlFromForm(filterForm));
                });

                filterForm?.querySelectorAll('input').forEach(function(input) {
                    input.addEventListener('change', function() {
                        if (catalogResults) {
                            debouncedCatalogLoad(catalogUrlFromForm(filterForm));
                        }
                    });

                    input.addEventListener('input', function() {
                        if (input.type === 'number' && catalogResults) {
                            debouncedCatalogLoad(catalogUrlFromForm(filterForm));
                        }
                    });
                });

                document.addEventListener('click', function(event) {
                    const filterButton = event.target.closest('[data-open-filters], #filterBtn');
                    if (filterButton) {
                        document.getElementById('filterPanel')?.classList.toggle('d-none');
                    }

                    const parentCategory = event.target.closest('.parent-category[data-category]');
                    if (parentCategory) {
                        const subcats = document.getElementById('subcats-' + parentCategory.dataset.category);
                        const target = document.getElementById('subcategory-container');

                        if (subcats && target) {
                            target.innerHTML = subcats.innerHTML;
                        }
                    }

                    const catalogLink = event.target.closest(
                    '[data-catalog-link], [data-catalog-pagination] a');
                    if (catalogLink && catalogResults) {
                        event.preventDefault();
                        searchForm?.reset();
                        loadCatalog(catalogLink.href);
                        document.getElementById('filterPanel')?.classList.remove('d-none');
                    }
                });

                window.addEventListener('popstate', function() {
                    if (catalogResults) {
                        loadCatalog(window.location.href, false);
                    }
                });

                document.addEventListener('submit', async function(event) {
                    const form = event.target.closest('form[data-ajax-cart], form[data-ajax-wishlist]');

                    if (!form) {
                        return;
                    }

                    event.preventDefault();
                    const button = form.querySelector('button[type="submit"], button:not([type])');
                    button?.setAttribute('disabled', 'disabled');

                    try {
                        const response = await fetch(form.action, {
                            method: form.method || 'POST',
                            body: new FormData(form),
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                        });
                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Request failed.');
                        }

                        if (form.matches('[data-ajax-cart]')) {
                            updateCount('[data-cart-count]', data.cart_count);

                            if (data.html && document.getElementById('cart-content')) {
                                document.getElementById('cart-content').innerHTML = data.html;
                            }
                        }

                        if (form.matches('[data-ajax-wishlist]')) {
                            updateCount('[data-wishlist-count]', data.wishlist_count);

                            document.querySelectorAll(
                                    `form[data-ajax-wishlist][data-product-id="${data.product_id}"] button`)
                                .forEach(function(wishlistButton) {
                                    const icon = wishlistButton.querySelector('i');
                                    wishlistButton.classList.toggle('btn-dark', data.in_wishlist);
                                    wishlistButton.classList.toggle('btn-light', !data.in_wishlist &&
                                        wishlistButton.classList.contains('btn-icon'));
                                    wishlistButton.classList.toggle('btn-outline-dark', !data
                                        .in_wishlist && !wishlistButton.classList.contains(
                                            'btn-icon'));
                                    icon?.classList.toggle('bi-heart-fill', data.in_wishlist);
                                    icon?.classList.toggle('bi-heart', !data.in_wishlist);

                                    if (!wishlistButton.classList.contains('btn-icon')) {
                                        wishlistButton.innerHTML =
                                            `<i class="bi ${data.in_wishlist ? 'bi-heart-fill' : 'bi-heart'} me-1"></i> ${data.in_wishlist ? 'Saved' : 'Wishlist'}`;
                                    }
                                });

                            if (!data.in_wishlist) {
                                document.querySelector(`[data-wishlist-card="${data.product_id}"]`)
                                ?.remove();
                            }
                        }

                        showFeedback(data.message || 'Updated.');
                    } catch (error) {
                        showFeedback(error.message, 'error');
                    } finally {
                        button?.removeAttribute('disabled');
                    }
                });
            });
        </script>
        @stack('scripts')
    </body>

    </html>

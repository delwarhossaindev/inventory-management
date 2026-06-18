<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventory') &middot; {{ config('app.name', 'Inventory') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background:#f4f6f9; }
        .sidebar { width:250px; min-height:100vh; background:#1f2937; }
        .sidebar a { color:#cbd5e1; text-decoration:none; }
        .sidebar a.active, .sidebar a:hover { color:#fff; background:#374151; }
        .sidebar .nav-link { border-radius:.4rem; padding:.6rem .9rem; margin-bottom:.2rem; }
        .sidebar button.nav-link { width:100%; border:0; background:none; }
        .sidebar button.nav-link:hover { color:#fff; }
        .sidebar .bi-chevron-down { transition:transform .2s; }
        .sidebar [aria-expanded="true"] .bi-chevron-down { transform:rotate(180deg); }
        .content { flex:1; min-width:0; }
        .table td, .table th { vertical-align:middle; }
        .text-truncate-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }

        /* Inventory page loader */
        .page-loader { position:fixed; inset:0; z-index:3000; display:flex; flex-direction:column;
            align-items:center; justify-content:center; background:rgba(244,246,249,.9);
            backdrop-filter:blur(2px); opacity:0; visibility:hidden; transition:opacity .2s ease; }
        .page-loader.active { opacity:1; visibility:visible; }
        .loader-box { position:relative; width:70px; height:70px; display:flex; align-items:center; justify-content:center; }
        .loader-box .bi { font-size:1.7rem; color:#1f2937; animation:loader-bob 1s ease-in-out infinite; }
        .loader-ring { position:absolute; inset:0; border:4px solid #d1d5db; border-top-color:#0d6efd;
            border-radius:50%; animation:loader-spin .8s linear infinite; }
        .loader-text { margin-top:.8rem; font-weight:600; letter-spacing:.08em; color:#374151; font-size:.85rem; text-transform:uppercase; }
        @keyframes loader-spin { to { transform:rotate(360deg); } }
        @keyframes loader-bob { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-5px); } }
    </style>
    @stack('styles')
</head>
<body>
<div id="page-loader" class="page-loader" aria-hidden="true">
    <div class="loader-box">
        <div class="loader-ring"></div>
        <i class="bi bi-box-seam"></i>
    </div>
    <div class="loader-text">Loading…</div>
</div>

<div class="d-flex">
    <aside class="sidebar text-white p-3 d-flex flex-column">
        <h5 class="px-2 mb-4 fw-bold"><i class="bi bi-box-seam me-2"></i>Inventory</h5>
        @include('layouts._nav')
        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button class="btn btn-sm btn-outline-light w-100"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
        </form>
    </aside>

    <div class="content">
        <header class="bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">@yield('heading', 'Dashboard')</h5>
            <span class="text-muted small">{{ auth()->user()->name ?? '' }}</span>
        </header>

        <main class="p-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    var loader = document.getElementById('page-loader');
    var show = function () { loader.classList.add('active'); };
    var hide = function () { loader.classList.remove('active'); };

    hide(); // ensure hidden once the page is ready

    // Show during same-origin link navigation.
    document.addEventListener('click', function (e) {
        var a = e.target.closest('a');
        if (!a) return;
        var href = a.getAttribute('href') || '';
        if (a.target === '_blank' || a.hasAttribute('download') || a.hasAttribute('data-bs-toggle')) return;
        if (href === '' || href.charAt(0) === '#' || href.indexOf('javascript:') === 0) return;
        try { if (new URL(a.href).origin !== location.origin) return; } catch (_) { return; }
        show();
    });

    // Show on form submit (skip if a handler/confirm cancelled it).
    document.addEventListener('submit', function (e) {
        if (e.defaultPrevented) return;
        show();
    });

    // Hide when returning via back/forward (bfcache restore).
    window.addEventListener('pageshow', hide);
})();
</script>
@stack('scripts')
</body>
</html>

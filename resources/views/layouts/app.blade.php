<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <!-- Outfit Font -->
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

        <!-- Global Dark Theme -->
        <style>
            :root {
                --bg-base:      #16110f;
                --bg-card:      #241c19;
                --bg-raised:    #2e2420;
                --border-color: #3b2f2b;
                --text-primary: #fdf5f1;
                --text-muted:   #c0aca3;
                --accent:       #ff6b2b;
                --accent-red:   #e63946;
                --gradient:     linear-gradient(135deg, #ff6b2b 0%, #e63946 100%);
            }

            *, body {
                font-family: 'Outfit', sans-serif !important;
            }

            body {
                background-color: var(--bg-base) !important;
                color: var(--text-primary) !important;
            }

            /* ── Bootstrap overrides ── */
            .card {
                background-color: var(--bg-card) !important;
                border: 1px solid var(--border-color) !important;
                border-radius: 1rem !important;
                color: var(--text-primary) !important;
            }
            .card-title  { color: var(--text-primary) !important; }
            .card-text   { color: var(--text-muted)   !important; }

            .bg-white  { background-color: var(--bg-card)   !important; }
            .bg-light  { background-color: var(--bg-raised) !important; }

            .text-dark, h1, h2, h3, h4, h5, h6 { color: var(--text-primary) !important; }
            .text-muted, .text-secondary         { color: var(--text-muted)   !important; }

            /* Inputs */
            .form-control, .form-select {
                background-color: #1a1412 !important;
                border-color: var(--border-color) !important;
                color: var(--text-primary) !important;
            }
            .form-control:focus, .form-select:focus {
                border-color: var(--accent) !important;
                box-shadow: 0 0 0 2px rgba(255,107,43,0.25) !important;
            }
            .form-control::placeholder { color: var(--text-muted) !important; }

            /* Borders & dividers */
            .border-bottom, .border-top, hr { border-color: var(--border-color) !important; }

            /* Buttons */
            .btn-primary {
                background: var(--gradient) !important;
                border: none !important;
                color: #fff !important;
            }
            .btn-primary:hover {
                background: linear-gradient(135deg, #e63946, #d62828) !important;
                box-shadow: 0 8px 20px rgba(230,57,70,0.3) !important;
                transform: translateY(-1px);
            }
            .btn-outline-primary {
                border-color: var(--accent) !important;
                color: var(--accent) !important;
            }
            .btn-outline-primary:hover {
                background-color: var(--accent) !important;
                color: #fff !important;
            }
            .btn-outline-secondary {
                border-color: var(--border-color) !important;
                color: var(--text-muted) !important;
            }
            .btn-outline-secondary:hover {
                background-color: var(--bg-raised) !important;
                color: var(--text-primary) !important;
            }
            .btn-outline-danger {
                border-color: var(--accent-red) !important;
                color: var(--accent-red) !important;
            }
            .btn-outline-danger:hover {
                background-color: var(--accent-red) !important;
                color: #fff !important;
            }

            /* Badges */
            .badge.bg-primary   { background-color: var(--accent) !important; }
            .badge.bg-secondary { background-color: var(--bg-raised) !important; color: var(--text-muted) !important; }

            /* Breadcrumb */
            .breadcrumb-item a { color: var(--accent) !important; }
            .breadcrumb-item.active { color: var(--text-muted) !important; }
            .breadcrumb-item+.breadcrumb-item::before { color: var(--border-color) !important; }

            /* Progress bars */
            .progress { background-color: var(--bg-raised) !important; }

            /* Alerts */
            .alert-danger  { background-color: rgba(230,57,70,0.15)  !important; border-color: var(--accent-red) !important; color: #ff8a94 !important; }
            .alert-success { background-color: rgba(34,197,94,0.12) !important; border-color: #16a34a !important; color: #4ade80 !important; }
            .alert-info    { background-color: rgba(99,102,241,0.12) !important; border-color: #6366f1 !important; color: #a5b4fc !important; }

            /* Pagination */
            .pagination .page-link {
                background-color: var(--bg-card)   !important;
                border-color:     var(--border-color) !important;
                color: var(--text-muted) !important;
            }
            .pagination .page-link:hover         { background-color: var(--bg-raised) !important; color: var(--accent) !important; }
            .pagination .page-item.active .page-link { background-color: var(--accent) !important; border-color: var(--accent) !important; color: #fff !important; }

            /* Table */
            .table { color: var(--text-primary) !important; }
            .table > :not(caption) > * > * { background-color: transparent !important; border-bottom-color: var(--border-color) !important; }
            .table thead th { color: var(--text-muted) !important; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; }
            .table-striped > tbody > tr:nth-of-type(odd) > * { background-color: rgba(255,255,255,0.02) !important; }

            /* Hover lift util */
            .hover-lift { transition: transform 0.25s cubic-bezier(.34,1.56,.64,1), box-shadow 0.25s ease; }
            .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 16px 32px rgba(0,0,0,0.4) !important; }
            .hover-shadow { transition: box-shadow 0.3s ease; }
            .hover-shadow:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.4) !important; }

            /* Dashboard card */
            .py-12 > div > .bg-white { border: 1px solid var(--border-color) !important; }

            /* Smooth global transitions */
            a { transition: color 0.2s ease; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased text-gray-900 dark:text-gray-100">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-[#1a1412] shadow-sm border-b border-[#3b2f2b]">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
</html>

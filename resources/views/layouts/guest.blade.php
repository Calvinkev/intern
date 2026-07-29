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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="background-color: #16110f; color: #fdf5f1;">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" class="flex flex-col items-center">
                    <i class="bi bi-rocket-takeoff text-[#ff6b2b]" style="font-size: 3rem;"></i>
                    <span class="text-2xl font-bold mt-2" style="background: linear-gradient(135deg, #ff6b2b 0%, #e63946 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">CODEBASE FOODS</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 shadow-2xl overflow-hidden sm:rounded-2xl" style="background-color: #241c19; border: 1px solid rgba(255,255,255,0.05);">
                {{ $slot }}
            </div>
        </div>
        <style>
            /* Overrides for default breeze components */
            input, select, textarea {
                background-color: #1a1412 !important;
                border-color: #3b2f2b !important;
                color: #fdf5f1 !important;
            }
            input:focus { border-color: #ff6b2b !important; box-shadow: 0 0 0 1px #ff6b2b !important; }
            .text-gray-600 { color: #c0aca3 !important; }
            .text-gray-900 { color: #fdf5f1 !important; }
            .border-gray-300 { border-color: #3b2f2b !important; }
            a.hover\:text-gray-900:hover { color: #ff6b2b !important; }
            /* Primary Button Override */
            button.bg-gray-800 {
                background: linear-gradient(135deg, #ff6b2b 0%, #e63946 100%) !important;
                color: white !important;
                border: none !important;
                transition: transform 0.2s, box-shadow 0.2s;
            }
            button.bg-gray-800:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(230,57,70,0.3) !important;
                background: linear-gradient(135deg, #e63946 0%, #d62828 100%) !important;
            }
        </style>
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    </body>
</html>

@props(['title' => 'Dashboard'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} - {{ config('app.name', 'Kendaraan KAS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome-all.min.css') }}">

    <!-- Accent Color CSS Variables -->
    @auth
    @php
        $palette = auth()->user()->getAccentPalette();
    @endphp
    <style>
        :root {
            --accent-50: {{ $palette['50'] }};
            --accent-100: {{ $palette['100'] }};
            --accent-200: {{ $palette['200'] }};
            --accent-300: {{ $palette['300'] }};
            --accent-400: {{ $palette['400'] }};
        }
    </style>
    @else
    <style>
        :root {
            --accent-50: #D9FBE6;
            --accent-100: #B7FFD1;
            --accent-200: #4ADE80;
            --accent-300: #22C55E;
            --accent-400: #16A34A;
        }
    </style>
    @endauth

    @stack('styles')
</head>
<body class="bg-bgray-50 dark:bg-darkblack-700">
    <!-- Layout Wrapper -->
    <div class="layout-wrapper active w-full">
        <div class="relative flex w-full">
            <!-- Sidebar -->
            @include('partials.sidebar')

            <!-- Sidebar Overlay (Mobile) -->
            <div class="aside-overlay fixed left-0 top-0 z-20 hidden h-full w-full bg-black/50 xl:hidden"></div>

            <!-- Main Content -->
            <div class="body-wrapper flex-1 min-h-screen overflow-x-hidden" id="body-wrapper" style="margin-left: 308px;">
                <style>
                    @media (max-width: 1279px) {
                        #body-wrapper { margin-left: 0 !important; }
                    }
                </style>
                <!-- Header -->
                @include('partials.header')

                <!-- Page Content -->
                <main class="w-full px-6 pb-6 pt-[80px] xl:pl-16 xl:pr-12 xl:pb-12 xl:pt-[100px]">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mb-4 rounded-lg bg-accent-50 p-4 text-accent-400" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 rounded-lg bg-error-50 p-4 text-error-300" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Page Header -->
                    @isset($header)
                        <div class="mb-6">
                            {{ $header }}
                        </div>
                    @endisset

                    <!-- Main Content Slot -->
                    {{ $slot }}
                </main>

                <!-- Footer -->
                <footer class="px-6 py-4 text-center text-sm text-bgray-500 dark:text-bgray-400 border-t border-bgray-200 dark:border-darkblack-400">
                    <span class="font-medium text-bgray-700 dark:text-bgray-300">Kendaraan</span>
                    <span class="mx-1">v{{ trim(file_get_contents(base_path('VERSION'))) }}</span>
                    <span class="mx-2">•</span>
                    <span>&copy; {{ date('Y') }} Keuskupan Agung Semarang</span>
                </footer>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    @stack('scripts')
</body>
</html>

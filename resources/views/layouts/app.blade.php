<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name', 'Kendaraan KAS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome-all.min.css') }}">

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
            <div class="body-wrapper flex-1 xl:ml-[308px]">
                <!-- Header -->
                @include('partials.header')

                <!-- Page Content -->
                <main class="w-full px-6 pb-6 pt-6 xl:px-12 xl:pb-12">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mb-4 rounded-lg bg-success-50 p-4 text-success-400" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 rounded-lg bg-error-50 p-4 text-error-300" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Page Header -->
                    @if(isset($header))
                        <div class="mb-6">
                            {{ $header }}
                        </div>
                    @endif

                    <!-- Main Content Slot -->
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    @stack('scripts')
</body>
</html>

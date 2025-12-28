@props(['title' => 'Login'])

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

    @stack('styles')
</head>
<body>
    <section class="min-h-screen bg-gradient-to-br from-white to-bgray-100 dark:from-darkblack-500 dark:to-darkblack-600">
        <div class="flex min-h-screen flex-col">
            <!-- Logo Header -->
            <header class="px-6 pt-8">
                <a href="/" class="inline-flex items-center">
                    <span class="text-2xl font-bold text-bgray-900 dark:text-white">Kendaraan</span>
                    <span class="ml-1 text-xl font-medium text-bgray-600 dark:text-bgray-300">KAS</span>
                </a>
            </header>

            <!-- Centered Content -->
            <div class="flex flex-1 items-center justify-center px-5 py-10">
                <div class="w-full max-w-[450px]">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </section>

    @stack('scripts')
</body>
</html>

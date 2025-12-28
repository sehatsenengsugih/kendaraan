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
    <section class="bg-white dark:bg-darkblack-500">
        <div class="flex min-h-screen flex-col justify-between lg:flex-row">
            <!-- Left Content -->
            <div class="px-5 pt-10 lg:w-1/2 xl:pl-12">
                <!-- Logo -->
                <header>
                    <a href="/" class="flex items-center">
                        <span class="text-2xl font-bold text-accent-300">Kendaraan</span>
                        <span class="ml-1 text-xl font-medium text-bgray-900 dark:text-white">KAS</span>
                    </a>
                </header>

                <!-- Content -->
                <div class="m-auto max-w-[450px] pb-16 pt-24">
                    {{ $slot }}
                </div>
            </div>

            <!-- Right Side - Illustration -->
            <div class="relative hidden bg-bgray-100 p-20 dark:bg-darkblack-600 lg:block lg:w-1/2">
                <div class="flex h-full flex-col items-center justify-center">
                    <!-- Illustration -->
                    <img src="{{ asset('assets/images/illustration/signin.svg') }}" alt="Illustration" class="max-w-md">

                    <!-- Text -->
                    <div class="mt-8 max-w-lg px-1.5 text-center">
                        <h3 class="mb-4 font-poppins text-4xl font-semibold text-bgray-900 dark:text-white">
                            Kelola Kendaraan dengan Mudah
                        </h3>
                        <p class="text-sm font-medium text-bgray-600 dark:text-bgray-50">
                            Sistem manajemen kendaraan terpadu untuk Keuskupan Agung Semarang.
                            Pantau pajak, servis, dan penugasan kendaraan dalam satu platform.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @stack('scripts')
</body>
</html>

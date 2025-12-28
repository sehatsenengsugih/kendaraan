<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Dashboard</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-300">Selamat datang, {{ Auth::user()->name ?? 'User' }}!</p>
            </div>
            <div>
                <span class="text-sm text-bgray-500">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>
    </x-slot>

    <!-- Stats Cards -->
    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <!-- Total Kendaraan -->
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-bgray-600 dark:text-bgray-300">Total Kendaraan</p>
                    <h3 class="mt-2 text-3xl font-bold text-bgray-900 dark:text-white">{{ $kendaraanStats['total'] }}</h3>
                    <p class="mt-1 text-xs text-bgray-500">Mobil: {{ $kendaraanStats['mobil'] }} | Motor: {{ $kendaraanStats['motor'] }}</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-accent-50">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M23.333 11.667H4.66699C3.19424 11.667 2.00033 12.8609 2.00033 14.3337V22.0003C2.00033 23.4731 3.19424 24.667 4.66699 24.667H23.333C24.8058 24.667 25.9997 23.4731 25.9997 22.0003V14.3337C25.9997 12.8609 24.8058 11.667 23.333 11.667Z" fill="#22C55E"/>
                        <path d="M5.33301 19.333C6.06939 19.333 6.66634 18.7361 6.66634 17.9997C6.66634 17.2633 6.06939 16.6663 5.33301 16.6663C4.59663 16.6663 3.99967 17.2633 3.99967 17.9997C3.99967 18.7361 4.59663 19.333 5.33301 19.333Z" fill="white"/>
                        <path d="M22.667 19.333C23.4034 19.333 24.0003 18.7361 24.0003 17.9997C24.0003 17.2633 23.4034 16.6663 22.667 16.6663C21.9306 16.6663 21.3337 17.2633 21.3337 17.9997C21.3337 18.7361 21.9306 19.333 22.667 19.333Z" fill="white"/>
                        <path d="M5.33301 11.667L7.66634 4.66699H20.333L22.6663 11.667" stroke="#22C55E" stroke-width="2.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pajak Akan Jatuh Tempo -->
        <a href="{{ route('pajak.index', ['due_soon' => 1]) }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-bgray-600 dark:text-bgray-300">Pajak Akan Jatuh Tempo</p>
                    <h3 class="mt-2 text-3xl font-bold text-warning-300">{{ $pajakStats['due_soon'] }}</h3>
                    <p class="mt-1 text-xs text-bgray-500">Dalam 30 hari ke depan</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-warning-100">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 25.667C20.4433 25.667 25.667 20.4433 25.667 14C25.667 7.55668 20.4433 2.33301 14 2.33301C7.55668 2.33301 2.33301 7.55668 2.33301 14C2.33301 20.4433 7.55668 25.667 14 25.667Z" fill="#FACC15"/>
                        <path d="M14 8.16699V14.0003L18.083 18.0837" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Pajak Terlambat -->
        <a href="{{ route('pajak.index', ['status' => 'terlambat']) }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-bgray-600 dark:text-bgray-300">Pajak Terlambat</p>
                    <h3 class="mt-2 text-3xl font-bold text-error-200">{{ $pajakStats['overdue'] }}</h3>
                    <p class="mt-1 text-xs text-bgray-500">Segera proses</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-error-50">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 25.667C20.4433 25.667 25.667 20.4433 25.667 14C25.667 7.55668 20.4433 2.33301 14 2.33301C7.55668 2.33301 2.33301 7.55668 2.33301 14C2.33301 20.4433 7.55668 25.667 14 25.667Z" fill="#FF4747"/>
                        <path d="M14 9.33301V15.1663M14 18.6663H14.0117" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Kendaraan Aktif -->
        <a href="{{ route('kendaraan.index', ['status' => 'aktif']) }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-bgray-600 dark:text-bgray-300">Kendaraan Aktif</p>
                    <h3 class="mt-2 text-3xl font-bold text-blue-500">{{ $kendaraanAktifStats['total'] }}</h3>
                    <p class="mt-1 text-xs text-bgray-500">
                        <i class="fa fa-car mr-1"></i>{{ $kendaraanAktifStats['mobil'] }} Mobil |
                        <i class="fa fa-motorcycle mr-1"></i>{{ $kendaraanAktifStats['motor'] }} Motor
                    </p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-50">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 25.667C20.4433 25.667 25.667 20.4433 25.667 14C25.667 7.55668 20.4433 2.33301 14 2.33301C7.55668 2.33301 2.33301 7.55668 2.33301 14C2.33301 20.4433 7.55668 25.667 14 25.667Z" fill="#3B82F6"/>
                        <path d="M10.5 14L13 16.5L18 11.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- Row 2: Mini Calendar + Pengingat Pajak -->
    <div class="mb-6 grid grid-cols-1 gap-6 xl:grid-cols-3">
        <!-- Mini Calendar Widget -->
        <div class="card xl:col-span-1" x-data="miniCalendar()" x-init="init()">
            <!-- Header with Navigation -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <button @click="prevMonth()" class="p-1 rounded-lg hover:bg-bgray-100 dark:hover:bg-darkblack-500 text-bgray-500 hover:text-bgray-700 dark:text-bgray-400 dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <h3 class="text-lg font-bold text-bgray-900 dark:text-white min-w-[140px] text-center" x-text="monthName">
                        {{ now()->translatedFormat('F Y') }}
                    </h3>
                    <button @click="nextMonth()" class="p-1 rounded-lg hover:bg-bgray-100 dark:hover:bg-darkblack-500 text-bgray-500 hover:text-bgray-700 dark:text-bgray-400 dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="goToToday()" x-show="!isCurrentMonth" class="text-xs text-accent-300 hover:underline">
                        Hari Ini
                    </button>
                    <a href="{{ route('calendar.index') }}" class="text-sm font-medium text-accent-300 hover:underline">
                        Buka
                    </a>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="select-none">
                <!-- Day Headers -->
                <div class="grid grid-cols-7 gap-1 mb-2">
                    <template x-for="day in ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']">
                        <div class="text-center text-xs font-medium text-bgray-500 dark:text-bgray-400 py-1" x-text="day"></div>
                    </template>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="grid grid-cols-7 gap-1">
                    <template x-for="i in 35">
                        <div class="aspect-square rounded-lg bg-bgray-100 dark:bg-darkblack-500 animate-pulse"></div>
                    </template>
                </div>

                <!-- Calendar Days -->
                <div x-show="!loading" class="grid grid-cols-7 gap-1">
                    <!-- Empty cells before first day -->
                    <template x-for="i in startDayOfWeek">
                        <div class="aspect-square"></div>
                    </template>

                    <!-- Days of month -->
                    <template x-for="day in daysInMonth">
                        <a :href="'{{ route('calendar.index') }}?date=' + getDateStr(day)"
                           class="aspect-square flex flex-col items-center justify-center rounded-lg text-sm transition-all"
                           :class="{
                               'bg-accent-300 text-white font-bold': isToday(day),
                               'bg-bgray-100 dark:bg-darkblack-500 hover:bg-bgray-200 dark:hover:bg-darkblack-400': hasEvents(day) && !isToday(day),
                               'hover:bg-bgray-50 dark:hover:bg-darkblack-500 text-bgray-700 dark:text-bgray-300': !hasEvents(day) && !isToday(day)
                           }">
                            <span :class="{ 'text-white': isToday(day) }" x-text="day"></span>
                            <div x-show="hasEvents(day)" class="flex gap-0.5 mt-0.5">
                                <template x-for="color in getEventColors(day).slice(0, 3)">
                                    <span class="w-1.5 h-1.5 rounded-full"
                                          :class="{
                                              'bg-white': isToday(day),
                                              'bg-error-400': !isToday(day) && color === 'error',
                                              'bg-orange-400': !isToday(day) && color === 'warning',
                                              'bg-blue-400': !isToday(day) && color === 'blue',
                                              'bg-purple': !isToday(day) && color === 'purple',
                                              'bg-indigo-400': !isToday(day) && color === 'indigo'
                                          }"></span>
                                </template>
                            </div>
                        </a>
                    </template>
                </div>
            </div>

            <!-- Legend -->
            <div class="mt-4 pt-3 border-t border-bgray-100 dark:border-darkblack-400">
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-error-400"></span>
                        <span class="text-bgray-500 dark:text-bgray-400">Pajak Terlambat</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                        <span class="text-bgray-500 dark:text-bgray-400">Pajak ≤7 hari</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                        <span class="text-bgray-500 dark:text-bgray-400">Pajak ≤30 hari</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-purple"></span>
                        <span class="text-bgray-500 dark:text-bgray-400">Servis</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengingat Pajak Section -->
        <div class="xl:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-bgray-900 dark:text-white">
                    <i class="fa fa-bell mr-2 text-warning-400"></i>
                    Pengingat Pajak
                </h3>
                <a href="{{ route('pajak.index') }}" class="text-sm font-medium text-accent-300 hover:underline">Lihat Semua</a>
            </div>

            <!-- Summary Cards -->
            <div class="mb-4 grid grid-cols-2 gap-3 md:grid-cols-4">
            <div class="rounded-lg bg-error-50 p-4 border-l-4 border-error-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-error-600">TERLAMBAT</p>
                        <p class="text-2xl font-bold text-error-500">{{ $pajakTerlambat->count() }}</p>
                    </div>
                    <i class="fa fa-exclamation-circle text-2xl text-error-300"></i>
                </div>
            </div>
            <div class="rounded-lg bg-orange-50 p-4 border-l-4 border-orange-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-orange-600">7 HARI</p>
                        <p class="text-2xl font-bold text-orange-500">{{ $pajak7Hari->count() }}</p>
                    </div>
                    <i class="fa fa-clock text-2xl text-orange-300"></i>
                </div>
            </div>
            <div class="rounded-lg bg-warning-50 p-4 border-l-4 border-warning-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-warning-600">30 HARI</p>
                        <p class="text-2xl font-bold text-warning-500">{{ $pajak30Hari->count() }}</p>
                    </div>
                    <i class="fa fa-calendar-alt text-2xl text-warning-300"></i>
                </div>
            </div>
            <div class="rounded-lg bg-blue-50 p-4 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-blue-600">6 BULAN</p>
                        <p class="text-2xl font-bold text-blue-500">{{ $pajak6Bulan->count() }}</p>
                    </div>
                    <i class="fa fa-calendar text-2xl text-blue-300"></i>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="card" x-data="{ activeTab: '{{ $pajakTerlambat->count() > 0 ? 'terlambat' : ($pajak7Hari->count() > 0 ? '7hari' : '30hari') }}' }">
            <div class="border-b border-bgray-200 dark:border-darkblack-400">
                <nav class="flex flex-wrap gap-2 -mb-px">
                    <button @click="activeTab = 'terlambat'"
                        :class="activeTab === 'terlambat' ? 'border-error-400 text-error-500 bg-error-50' : 'border-transparent text-bgray-500 hover:text-bgray-700 hover:border-bgray-300'"
                        class="px-4 py-3 text-sm font-medium border-b-2 rounded-t-lg transition-all">
                        <i class="fa fa-exclamation-triangle mr-1"></i>
                        Terlambat
                        @if($pajakTerlambat->count() > 0)
                            <span class="ml-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-error-400 rounded-full">{{ $pajakTerlambat->count() }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = '7hari'"
                        :class="activeTab === '7hari' ? 'border-orange-400 text-orange-500 bg-orange-50' : 'border-transparent text-bgray-500 hover:text-bgray-700 hover:border-bgray-300'"
                        class="px-4 py-3 text-sm font-medium border-b-2 rounded-t-lg transition-all">
                        <i class="fa fa-bolt mr-1"></i>
                        7 Hari
                        @if($pajak7Hari->count() > 0)
                            <span class="ml-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-orange-400 rounded-full">{{ $pajak7Hari->count() }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = '30hari'"
                        :class="activeTab === '30hari' ? 'border-warning-400 text-warning-500 bg-warning-50' : 'border-transparent text-bgray-500 hover:text-bgray-700 hover:border-bgray-300'"
                        class="px-4 py-3 text-sm font-medium border-b-2 rounded-t-lg transition-all">
                        <i class="fa fa-calendar-week mr-1"></i>
                        30 Hari
                        @if($pajak30Hari->count() > 0)
                            <span class="ml-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-warning-400 rounded-full">{{ $pajak30Hari->count() }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = '6bulan'"
                        :class="activeTab === '6bulan' ? 'border-blue-400 text-blue-500 bg-blue-50' : 'border-transparent text-bgray-500 hover:text-bgray-700 hover:border-bgray-300'"
                        class="px-4 py-3 text-sm font-medium border-b-2 rounded-t-lg transition-all">
                        <i class="fa fa-calendar mr-1"></i>
                        6 Bulan
                        @if($pajak6Bulan->count() > 0)
                            <span class="ml-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-blue-400 rounded-full">{{ $pajak6Bulan->count() }}</span>
                        @endif
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="mt-4">
                <!-- Terlambat Tab -->
                <div x-show="activeTab === 'terlambat'" x-cloak>
                    @if($pajakTerlambat->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-error-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-error-700">Plat Nomor</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-error-700">Kendaraan</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-error-700">Jenis Pajak</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-error-700">Jatuh Tempo</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-error-700">Terlambat</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-bgray-100">
                                    @foreach($pajakTerlambat as $pajak)
                                        <tr class="hover:bg-error-50/50">
                                            <td class="px-4 py-3">
                                                <a href="{{ route('kendaraan.show', $pajak->kendaraan) }}" class="font-mono font-medium text-bgray-900 hover:text-error-500">
                                                    {{ $pajak->kendaraan->plat_nomor }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-bgray-600">
                                                {{ $pajak->kendaraan->merk->nama ?? '' }} {{ $pajak->kendaraan->nama_model }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex rounded-full bg-bgray-100 px-2 py-1 text-xs font-medium text-bgray-700">
                                                    {{ ucfirst($pajak->jenis) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-bgray-900">
                                                {{ $pajak->tanggal_jatuh_tempo->format('d M Y') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex rounded-full bg-error-100 px-2 py-1 text-xs font-bold text-error-600">
                                                    {{ abs($pajak->days_until_due) }} hari
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-8 text-center">
                            <i class="fa fa-check-circle text-4xl text-accent-300 mb-2"></i>
                            <p class="text-bgray-500">Tidak ada pajak yang terlambat</p>
                        </div>
                    @endif
                </div>

                <!-- 7 Hari Tab -->
                <div x-show="activeTab === '7hari'" x-cloak>
                    @if($pajak7Hari->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-orange-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-orange-700">Plat Nomor</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-orange-700">Kendaraan</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-orange-700">Jenis Pajak</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-orange-700">Jatuh Tempo</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-orange-700">Sisa Waktu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-bgray-100">
                                    @foreach($pajak7Hari as $pajak)
                                        <tr class="hover:bg-orange-50/50">
                                            <td class="px-4 py-3">
                                                <a href="{{ route('kendaraan.show', $pajak->kendaraan) }}" class="font-mono font-medium text-bgray-900 hover:text-orange-500">
                                                    {{ $pajak->kendaraan->plat_nomor }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-bgray-600">
                                                {{ $pajak->kendaraan->merk->nama ?? '' }} {{ $pajak->kendaraan->nama_model }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex rounded-full bg-bgray-100 px-2 py-1 text-xs font-medium text-bgray-700">
                                                    {{ ucfirst($pajak->jenis) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-bgray-900">
                                                {{ $pajak->tanggal_jatuh_tempo->format('d M Y') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex rounded-full bg-orange-100 px-2 py-1 text-xs font-bold text-orange-600">
                                                    {{ $pajak->days_until_due }} hari lagi
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-8 text-center">
                            <i class="fa fa-calendar-check text-4xl text-accent-300 mb-2"></i>
                            <p class="text-bgray-500">Tidak ada pajak jatuh tempo dalam 7 hari ke depan</p>
                        </div>
                    @endif
                </div>

                <!-- 30 Hari Tab -->
                <div x-show="activeTab === '30hari'" x-cloak>
                    @if($pajak30Hari->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-warning-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-warning-700">Plat Nomor</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-warning-700">Kendaraan</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-warning-700">Jenis Pajak</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-warning-700">Jatuh Tempo</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-warning-700">Sisa Waktu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-bgray-100">
                                    @foreach($pajak30Hari as $pajak)
                                        <tr class="hover:bg-warning-50/50">
                                            <td class="px-4 py-3">
                                                <a href="{{ route('kendaraan.show', $pajak->kendaraan) }}" class="font-mono font-medium text-bgray-900 hover:text-warning-500">
                                                    {{ $pajak->kendaraan->plat_nomor }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-bgray-600">
                                                {{ $pajak->kendaraan->merk->nama ?? '' }} {{ $pajak->kendaraan->nama_model }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex rounded-full bg-bgray-100 px-2 py-1 text-xs font-medium text-bgray-700">
                                                    {{ ucfirst($pajak->jenis) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-bgray-900">
                                                {{ $pajak->tanggal_jatuh_tempo->format('d M Y') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex rounded-full bg-warning-100 px-2 py-1 text-xs font-bold text-warning-600">
                                                    {{ $pajak->days_until_due }} hari lagi
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-8 text-center">
                            <i class="fa fa-calendar-check text-4xl text-accent-300 mb-2"></i>
                            <p class="text-bgray-500">Tidak ada pajak jatuh tempo dalam 8-30 hari ke depan</p>
                        </div>
                    @endif
                </div>

                <!-- 6 Bulan Tab -->
                <div x-show="activeTab === '6bulan'" x-cloak>
                    @if($pajak6Bulan->count() > 0)
                        <div class="overflow-x-auto max-h-96 overflow-y-auto">
                            <table class="w-full">
                                <thead class="bg-blue-50 sticky top-0">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Plat Nomor</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Kendaraan</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Jenis Pajak</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Jatuh Tempo</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700">Sisa Waktu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-bgray-100">
                                    @foreach($pajak6Bulan as $pajak)
                                        <tr class="hover:bg-blue-50/50">
                                            <td class="px-4 py-3">
                                                <a href="{{ route('kendaraan.show', $pajak->kendaraan) }}" class="font-mono font-medium text-bgray-900 hover:text-blue-500">
                                                    {{ $pajak->kendaraan->plat_nomor }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-bgray-600">
                                                {{ $pajak->kendaraan->merk->nama ?? '' }} {{ $pajak->kendaraan->nama_model }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex rounded-full bg-bgray-100 px-2 py-1 text-xs font-medium text-bgray-700">
                                                    {{ ucfirst($pajak->jenis) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-bgray-900">
                                                {{ $pajak->tanggal_jatuh_tempo->format('d M Y') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-bold text-blue-600">
                                                    {{ $pajak->days_until_due }} hari lagi
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-8 text-center">
                            <i class="fa fa-calendar-check text-4xl text-accent-300 mb-2"></i>
                            <p class="text-bgray-500">Tidak ada pajak jatuh tempo dalam 6 bulan ke depan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Chart: Umur Kendaraan -->
    <div class="mb-6 grid grid-cols-1 gap-6 xl:grid-cols-2">
        <!-- Bar Chart -->
        <div class="card">
            <h3 class="mb-4 text-lg font-bold text-bgray-900 dark:text-white">
                <i class="fa fa-chart-bar mr-2 text-accent-300"></i>
                Distribusi Umur Kendaraan
            </h3>
            <div class="relative h-64">
                <canvas id="umurKendaraanChart"></canvas>
            </div>
            <div class="mt-4 text-center text-sm text-bgray-500">
                Total: {{ array_sum($umurData) }} kendaraan
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="card">
            <h3 class="mb-4 text-lg font-bold text-bgray-900 dark:text-white">
                <i class="fa fa-clock mr-2 text-warning-300"></i>
                Ringkasan Umur Kendaraan
            </h3>
            <div class="space-y-3">
                @php
                    $colors = [
                        '0-5 tahun' => ['bg' => 'bg-accent-100', 'text' => 'text-accent-400', 'bar' => 'bg-accent-400'],
                        '6-10 tahun' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-500', 'bar' => 'bg-blue-400'],
                        '11-15 tahun' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-500', 'bar' => 'bg-warning-400'],
                        '16-20 tahun' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-500', 'bar' => 'bg-orange-400'],
                        '> 20 tahun' => ['bg' => 'bg-error-100', 'text' => 'text-error-400', 'bar' => 'bg-error-300'],
                    ];
                    $total = array_sum($umurData) ?: 1;
                @endphp
                @foreach($umurData as $range => $count)
                    @php $pct = round(($count / $total) * 100); @endphp
                    <div class="rounded-lg {{ $colors[$range]['bg'] ?? 'bg-bgray-100' }} p-3">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium {{ $colors[$range]['text'] ?? 'text-bgray-700' }}">{{ $range }}</span>
                            <span class="font-bold {{ $colors[$range]['text'] ?? 'text-bgray-900' }}">{{ $count }} unit</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-white/50">
                            <div class="h-2 rounded-full {{ $colors[$range]['bar'] ?? 'bg-bgray-400' }}" style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="text-right text-xs mt-1 {{ $colors[$range]['text'] ?? 'text-bgray-500' }}">{{ $pct }}%</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Chart: Kendaraan per Merk -->
    <div class="mb-6">
        <h3 class="mb-4 text-xl font-bold text-bgray-900 dark:text-white">
            <i class="fa fa-car-side mr-2 text-purple"></i>
            Kendaraan Berdasarkan Merk
        </h3>
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <!-- Mobil Chart -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-bgray-900 dark:text-white">
                        <i class="fa fa-car mr-2 text-blue-500"></i>
                        Mobil
                    </h4>
                    <span class="rounded-full bg-blue-100 px-3 py-1 text-sm font-bold text-blue-600">
                        {{ $merkMobil->sum('jumlah') }} unit
                    </span>
                </div>
                <div class="relative h-72">
                    <canvas id="merkMobilChart"></canvas>
                </div>
            </div>

            <!-- Motor Chart -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-bgray-900 dark:text-white">
                        <i class="fa fa-motorcycle mr-2 text-orange-500"></i>
                        Motor
                    </h4>
                    <span class="rounded-full bg-orange-100 px-3 py-1 text-sm font-bold text-orange-600">
                        {{ $merkMotor->sum('jumlah') }} unit
                    </span>
                </div>
                <div class="relative h-72">
                    <canvas id="merkMotorChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Detail Table -->
        <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-2">
            <!-- Mobil Table -->
            <div class="card">
                <h4 class="mb-4 font-semibold text-bgray-900 dark:text-white">Detail Mobil per Merk</h4>
                <div class="space-y-2">
                    @php $totalMobil = $merkMobil->sum('jumlah') ?: 1; @endphp
                    @foreach($merkMobil as $item)
                        @php $pct = round(($item['jumlah'] / $totalMobil) * 100); @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-24 text-sm font-medium text-bgray-700 dark:text-bgray-300">{{ $item['merk'] }}</div>
                            <div class="flex-1 h-6 bg-bgray-100 dark:bg-darkblack-500 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-end pr-2"
                                     style="width: {{ max($pct, 8) }}%">
                                    <span class="text-xs font-bold text-white">{{ $item['jumlah'] }}</span>
                                </div>
                            </div>
                            <div class="w-12 text-right text-sm text-bgray-500">{{ $pct }}%</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Motor Table -->
            <div class="card">
                <h4 class="mb-4 font-semibold text-bgray-900 dark:text-white">Detail Motor per Merk</h4>
                <div class="space-y-2">
                    @php $totalMotor = $merkMotor->sum('jumlah') ?: 1; @endphp
                    @foreach($merkMotor as $item)
                        @php $pct = round(($item['jumlah'] / $totalMotor) * 100); @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-24 text-sm font-medium text-bgray-700 dark:text-bgray-300">{{ $item['merk'] }}</div>
                            <div class="flex-1 h-6 bg-bgray-100 dark:bg-darkblack-500 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-orange-400 to-orange-600 rounded-full flex items-center justify-end pr-2"
                                     style="width: {{ max($pct, 8) }}%">
                                    <span class="text-xs font-bold text-white">{{ $item['jumlah'] }}</span>
                                </div>
                            </div>
                            <div class="w-12 text-right text-sm text-bgray-500">{{ $pct }}%</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Penugasan Aktif -->
    <div class="mb-6">
        <div class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-bgray-600 dark:text-bgray-300">Penugasan Aktif</p>
                    <h3 class="mt-2 text-3xl font-bold text-accent-400">{{ $penugasanAktif }}</h3>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-accent-50">
                    <i class="fa fa-users text-2xl text-accent-400"></i>
                </div>
            </div>
            <a href="{{ route('penugasan.index', ['status' => 'aktif']) }}" class="mt-4 block text-center text-sm font-medium text-accent-300 hover:underline">
                Lihat Semua Penugasan
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6">
        <h3 class="mb-4 text-lg font-bold text-bgray-900 dark:text-white">Aksi Cepat</h3>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <a href="{{ route('kendaraan.create') }}" class="flex flex-col items-center rounded-lg bg-white p-4 text-center hover:shadow-lg transition-shadow dark:bg-darkblack-600">
                <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-full bg-accent-50">
                    <i class="fa fa-car text-accent-400"></i>
                </div>
                <span class="text-sm font-medium text-bgray-900 dark:text-white">Tambah Kendaraan</span>
            </a>
            <a href="{{ route('pajak.create') }}" class="flex flex-col items-center rounded-lg bg-white p-4 text-center hover:shadow-lg transition-shadow dark:bg-darkblack-600">
                <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-full bg-warning-50">
                    <i class="fa fa-file-invoice text-warning-400"></i>
                </div>
                <span class="text-sm font-medium text-bgray-900 dark:text-white">Tambah Pajak</span>
            </a>
            <a href="{{ route('servis.create') }}" class="flex flex-col items-center rounded-lg bg-white p-4 text-center hover:shadow-lg transition-shadow dark:bg-darkblack-600">
                <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-full bg-purple/10">
                    <i class="fa fa-wrench text-purple"></i>
                </div>
                <span class="text-sm font-medium text-bgray-900 dark:text-white">Tambah Servis</span>
            </a>
            <a href="{{ route('penugasan.create') }}" class="flex flex-col items-center rounded-lg bg-white p-4 text-center hover:shadow-lg transition-shadow dark:bg-darkblack-600">
                <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-full bg-blue-50">
                    <i class="fa fa-user-plus text-blue-500"></i>
                </div>
                <span class="text-sm font-medium text-bgray-900 dark:text-white">Tambah Penugasan</span>
            </a>
        </div>
    </div>
@push('scripts')
<script>
// Mini Calendar Alpine.js Component
function miniCalendar() {
    return {
        year: {{ now()->year }},
        month: {{ now()->month }},
        monthName: '{{ now()->translatedFormat('F Y') }}',
        daysInMonth: {{ now()->daysInMonth }},
        startDayOfWeek: {{ now()->startOfMonth()->dayOfWeek }},
        events: {!! json_encode($calendarEvents) !!},
        loading: false,
        todayYear: {{ now()->year }},
        todayMonth: {{ now()->month }},
        todayDay: {{ now()->day }},

        get isCurrentMonth() {
            return this.year === this.todayYear && this.month === this.todayMonth;
        },

        init() {
            // Data already loaded from server for current month
        },

        async fetchMonth() {
            this.loading = true;
            try {
                const response = await fetch(`/api/calendar/mini-events?year=${this.year}&month=${this.month}`);
                const data = await response.json();
                this.monthName = data.monthName;
                this.daysInMonth = data.daysInMonth;
                this.startDayOfWeek = data.startDayOfWeek;
                this.events = data.events;
            } catch (error) {
                console.error('Failed to fetch calendar data:', error);
            } finally {
                this.loading = false;
            }
        },

        prevMonth() {
            this.month--;
            if (this.month < 1) {
                this.month = 12;
                this.year--;
            }
            this.fetchMonth();
        },

        nextMonth() {
            this.month++;
            if (this.month > 12) {
                this.month = 1;
                this.year++;
            }
            this.fetchMonth();
        },

        goToToday() {
            this.year = this.todayYear;
            this.month = this.todayMonth;
            this.fetchMonth();
        },

        isToday(day) {
            return this.year === this.todayYear &&
                   this.month === this.todayMonth &&
                   day === this.todayDay;
        },

        getDateStr(day) {
            const m = String(this.month).padStart(2, '0');
            const d = String(day).padStart(2, '0');
            return `${this.year}-${m}-${d}`;
        },

        hasEvents(day) {
            const dateStr = this.getDateStr(day);
            return this.events && this.events[dateStr] && this.events[dateStr].length > 0;
        },

        getEventColors(day) {
            const dateStr = this.getDateStr(day);
            return this.events && this.events[dateStr] ? this.events[dateStr] : [];
        }
    };
}
</script>
<script src="{{ asset('assets/js/chart.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Grafik Distribusi Umur Kendaraan (Bar Chart)
    const ctx = document.getElementById('umurKendaraanChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($umurData)) !!},
                datasets: [{
                    label: 'Jumlah Kendaraan',
                    data: {!! json_encode(array_values($umurData)) !!},
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',   // hijau - 0-5 tahun
                        'rgba(59, 130, 246, 0.8)', // biru - 6-10 tahun
                        'rgba(250, 204, 21, 0.8)', // kuning - 11-15 tahun
                        'rgba(249, 115, 22, 0.8)', // oranye - 16-20 tahun
                        'rgba(239, 68, 68, 0.8)',  // merah - > 20 tahun
                    ],
                    borderColor: [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(250, 204, 21)',
                        'rgb(249, 115, 22)',
                        'rgb(239, 68, 68)',
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.raw / total) * 100).toFixed(1);
                                return context.raw + ' kendaraan (' + percentage + '%)';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10,
                            font: { size: 12 }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 11 }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Grafik Merk Mobil (Doughnut Chart)
    const mobilCtx = document.getElementById('merkMobilChart');
    if (mobilCtx) {
        const mobilData = {!! json_encode($merkMobil->values()) !!};
        const mobilLabels = mobilData.map(item => item.merk);
        const mobilValues = mobilData.map(item => item.jumlah);

        // Palet warna untuk mobil (nuansa biru)
        const mobilColors = [
            'rgba(59, 130, 246, 0.9)',   // biru-500
            'rgba(99, 102, 241, 0.9)',   // indigo-500
            'rgba(139, 92, 246, 0.9)',   // violet-500
            'rgba(168, 85, 247, 0.9)',   // ungu-500
            'rgba(37, 99, 235, 0.9)',    // biru-600
            'rgba(79, 70, 229, 0.9)',    // indigo-600
            'rgba(124, 58, 237, 0.9)',   // violet-600
            'rgba(147, 51, 234, 0.9)',   // ungu-600
            'rgba(29, 78, 216, 0.9)',    // biru-700
            'rgba(67, 56, 202, 0.9)',    // indigo-700
        ];

        new Chart(mobilCtx, {
            type: 'doughnut',
            data: {
                labels: mobilLabels,
                datasets: [{
                    data: mobilValues,
                    backgroundColor: mobilColors.slice(0, mobilLabels.length),
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '55%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 11 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.raw / total) * 100).toFixed(1);
                                return context.label + ': ' + context.raw + ' unit (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Grafik Merk Motor (Doughnut Chart)
    const motorCtx = document.getElementById('merkMotorChart');
    if (motorCtx) {
        const motorData = {!! json_encode($merkMotor->values()) !!};
        const motorLabels = motorData.map(item => item.merk);
        const motorValues = motorData.map(item => item.jumlah);

        // Palet warna untuk motor (nuansa oranye/hangat)
        const motorColors = [
            'rgba(249, 115, 22, 0.9)',   // oranye-500
            'rgba(245, 158, 11, 0.9)',   // kuning-500
            'rgba(234, 88, 12, 0.9)',    // oranye-600
            'rgba(217, 119, 6, 0.9)',    // kuning-600
            'rgba(251, 146, 60, 0.9)',   // oranye-400
            'rgba(252, 211, 77, 0.9)',   // kuning-300
            'rgba(194, 65, 12, 0.9)',    // oranye-700
            'rgba(180, 83, 9, 0.9)',     // kuning-700
        ];

        new Chart(motorCtx, {
            type: 'doughnut',
            data: {
                labels: motorLabels,
                datasets: [{
                    data: motorValues,
                    backgroundColor: motorColors.slice(0, motorLabels.length),
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '55%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 11 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.raw / total) * 100).toFixed(1);
                                return context.label + ': ' + context.raw + ' unit (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
</x-app-layout>

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
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-success-50">
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

        <!-- Servis Akan Jatuh Tempo -->
        <a href="{{ route('servis.index') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-bgray-600 dark:text-bgray-300">Servis Akan Jatuh Tempo</p>
                    <h3 class="mt-2 text-3xl font-bold text-purple">{{ $servisStats['due_soon'] }}</h3>
                    <p class="mt-1 text-xs text-bgray-500">Dalam 30 hari ke depan</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-purple/10">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 9.33301L17.5 5.83301L14 9.33301" stroke="#936DFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17.5 5.83301V17.4997C17.5 18.7703 16.4706 19.7997 15.2 19.7997H7" stroke="#936DFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 18.667L10.5 22.167L14 18.667" stroke="#936DFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10.5 22.167V10.5003C10.5 9.22968 11.5294 8.20033 12.8 8.20033H21" stroke="#936DFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <!-- Pajak Jatuh Tempo Table -->
        <div class="card xl:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-bgray-900 dark:text-white">Pajak Akan Jatuh Tempo</h3>
                <a href="{{ route('pajak.index', ['due_soon' => 1]) }}" class="text-sm font-medium text-success-300 hover:underline">Lihat Semua</a>
            </div>
            <div class="table-wrapper">
                <table class="table">
                    <thead class="bg-bgray-50 dark:bg-darkblack-500">
                        <tr>
                            <th>No. Plat</th>
                            <th>Kendaraan</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-bgray-200 dark:divide-darkblack-400">
                        @forelse($pajakJatuhTempo as $pajak)
                            <tr class="hover:bg-bgray-50 dark:hover:bg-darkblack-500">
                                <td>
                                    <a href="{{ route('pajak.show', $pajak) }}" class="font-medium text-bgray-900 hover:text-success-300 dark:text-white">
                                        {{ $pajak->kendaraan->plat_nomor }}
                                    </a>
                                </td>
                                <td class="text-bgray-600 dark:text-bgray-300">
                                    {{ $pajak->kendaraan->merk->nama ?? '' }} {{ $pajak->kendaraan->nama_model }}
                                </td>
                                <td>
                                    <span class="text-bgray-900 dark:text-white">{{ $pajak->tanggal_jatuh_tempo->format('d M Y') }}</span>
                                    @if($pajak->days_until_due < 0)
                                        <p class="text-xs text-error-300">{{ abs($pajak->days_until_due) }} hari terlambat</p>
                                    @else
                                        <p class="text-xs text-warning-400">{{ $pajak->days_until_due }} hari lagi</p>
                                    @endif
                                </td>
                                <td>
                                    @if($pajak->isOverdue())
                                        <span class="inline-flex rounded-full bg-error-50 px-2 py-1 text-xs font-medium text-error-300">
                                            Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-warning-50 px-2 py-1 text-xs font-medium text-warning-400">
                                            Menunggu
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-bgray-500">
                                    Tidak ada pajak yang akan jatuh tempo dalam 30 hari ke depan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="space-y-6">
            <!-- Kendaraan per Garasi -->
            <div class="card">
                <h3 class="mb-4 text-lg font-bold text-bgray-900 dark:text-white">Kendaraan per Garasi</h3>
                <div class="space-y-4">
                    @forelse($kendaraanPerGarasi as $garasi)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-bgray-600 dark:text-bgray-300">{{ Str::limit($garasi->nama, 25) }}</span>
                            <span class="text-sm font-semibold text-bgray-900 dark:text-white">{{ $garasi->kendaraan_count }}</span>
                        </div>
                    @empty
                        <p class="text-center text-sm text-bgray-500">Belum ada data garasi</p>
                    @endforelse
                </div>
                <a href="{{ route('garasi.index') }}" class="mt-4 block text-center text-sm font-medium text-success-300 hover:underline">
                    Lihat Semua Garasi
                </a>
            </div>

            <!-- Penugasan Aktif -->
            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-bgray-600 dark:text-bgray-300">Penugasan Aktif</p>
                        <h3 class="mt-2 text-3xl font-bold text-success-400">{{ $penugasanAktif }}</h3>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-success-50">
                        <i class="fa fa-users text-2xl text-success-400"></i>
                    </div>
                </div>
                <a href="{{ route('penugasan.index', ['status' => 'aktif']) }}" class="mt-4 block text-center text-sm font-medium text-success-300 hover:underline">
                    Lihat Semua Penugasan
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6">
        <h3 class="mb-4 text-lg font-bold text-bgray-900 dark:text-white">Aksi Cepat</h3>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <a href="{{ route('kendaraan.create') }}" class="flex flex-col items-center rounded-lg bg-white p-4 text-center hover:shadow-lg transition-shadow dark:bg-darkblack-600">
                <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-full bg-success-50">
                    <i class="fa fa-car text-success-400"></i>
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
</x-app-layout>

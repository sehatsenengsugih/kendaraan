<x-app-layout>
    <x-slot name="title">Detail Paroki</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('paroki.index') }}"
                    class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">{{ $paroki->nama }}</h2>
                    <p class="text-sm text-bgray-600 dark:text-bgray-50">{{ $paroki->kevikepan->nama ?? '-' }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('paroki.edit', $paroki) }}"
                    class="inline-flex items-center rounded-lg border border-bgray-300 px-4 py-2 font-semibold text-bgray-600 transition-all hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    <i class="fa fa-edit mr-2"></i> Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Info Card -->
        <div class="lg:col-span-1">
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Informasi Paroki</h3>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Status</dt>
                        <dd>
                            @if($paroki->is_active)
                                <span class="inline-flex rounded-full bg-success-50 px-2 py-1 text-xs font-medium text-success-400">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-bgray-100 px-2 py-1 text-xs font-medium text-bgray-600">
                                    Tidak Aktif
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Kevikepan</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $paroki->kevikepan->nama ?? '-' }}</dd>
                    </div>
                    @if($paroki->alamat)
                        <div>
                            <dt class="text-sm text-bgray-500 dark:text-bgray-50">Alamat</dt>
                            <dd class="text-bgray-900 dark:text-white">{{ $paroki->alamat }}</dd>
                        </div>
                    @endif
                    @if($paroki->kota)
                        <div>
                            <dt class="text-sm text-bgray-500 dark:text-bgray-50">Kota</dt>
                            <dd class="text-bgray-900 dark:text-white">{{ $paroki->kota }}</dd>
                        </div>
                    @endif
                    @if($paroki->telepon)
                        <div>
                            <dt class="text-sm text-bgray-500 dark:text-bgray-50">Telepon</dt>
                            <dd>
                                <a href="tel:{{ $paroki->telepon }}" class="text-success-300 hover:underline">
                                    {{ $paroki->telepon }}
                                </a>
                            </dd>
                        </div>
                    @endif
                    @if($paroki->email)
                        <div>
                            <dt class="text-sm text-bgray-500 dark:text-bgray-50">Email</dt>
                            <dd>
                                <a href="mailto:{{ $paroki->email }}" class="text-success-300 hover:underline">
                                    {{ $paroki->email }}
                                </a>
                            </dd>
                        </div>
                    @endif
                </dl>

                <hr class="my-4 border-bgray-200 dark:border-darkblack-400">
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-bgray-500 dark:text-bgray-50">Dibuat</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $paroki->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-bgray-500 dark:text-bgray-50">Diperbarui</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $paroki->updated_at->format('d M Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Riwayat Pemakai & Kendaraan Dipinjam -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Kendaraan Dipinjam -->
            @if($paroki->kendaraanDipinjam->count() > 0)
                <div class="rounded-lg bg-white dark:bg-darkblack-600">
                    <div class="flex items-center justify-between border-b border-bgray-200 p-6 dark:border-darkblack-400">
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white">
                            Kendaraan Dipinjam
                            <span class="ml-2 rounded-full bg-warning-50 px-2 py-1 text-sm text-warning-400">
                                {{ $paroki->kendaraanDipinjam->count() }} unit
                            </span>
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b border-bgray-200 dark:border-darkblack-400">
                                <tr class="text-left">
                                    <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Kendaraan</th>
                                    <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Plat Nomor</th>
                                    <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Tanggal Pinjam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paroki->kendaraanDipinjam as $k)
                                    <tr class="border-b border-bgray-200 last:border-0 dark:border-darkblack-400">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('kendaraan.show', $k) }}" class="font-medium text-bgray-900 hover:text-success-300 dark:text-white">
                                                {{ $k->merk->nama ?? '' }} {{ $k->nama_model }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-bgray-900 dark:text-white">
                                            {{ $k->plat_nomor }}
                                        </td>
                                        <td class="px-6 py-4 text-bgray-900 dark:text-white">
                                            {{ $k->tanggal_pinjam ? $k->tanggal_pinjam->format('d M Y') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Riwayat Pemakai -->
            <div class="rounded-lg bg-white dark:bg-darkblack-600">
                <div class="flex items-center justify-between border-b border-bgray-200 p-6 dark:border-darkblack-400">
                    <h3 class="text-lg font-semibold text-bgray-900 dark:text-white">
                        Riwayat Pemakai Kendaraan
                        <span class="ml-2 rounded-full bg-success-50 px-2 py-1 text-sm text-success-400">
                            {{ $paroki->riwayatPemakai->count() }} data
                        </span>
                    </h3>
                </div>

                @if($paroki->riwayatPemakai->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b border-bgray-200 dark:border-darkblack-400">
                                <tr class="text-left">
                                    <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Kendaraan</th>
                                    <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Periode</th>
                                    <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paroki->riwayatPemakai as $riwayat)
                                    <tr class="border-b border-bgray-200 last:border-0 dark:border-darkblack-400">
                                        <td class="px-6 py-4">
                                            @if($riwayat->kendaraan)
                                                <a href="{{ route('kendaraan.show', $riwayat->kendaraan) }}" class="font-medium text-bgray-900 hover:text-success-300 dark:text-white">
                                                    {{ $riwayat->kendaraan->merk->nama ?? '' }} {{ $riwayat->kendaraan->nama_model }}
                                                </a>
                                                <p class="text-sm text-bgray-500 dark:text-bgray-50">{{ $riwayat->kendaraan->plat_nomor }}</p>
                                            @else
                                                <span class="text-bgray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-bgray-900 dark:text-white">
                                            {{ $riwayat->tanggal_mulai->format('d M Y') }}
                                            -
                                            {{ $riwayat->tanggal_selesai ? $riwayat->tanggal_selesai->format('d M Y') : 'Sekarang' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($riwayat->isAktif())
                                                <span class="inline-flex rounded-full bg-success-50 px-2 py-1 text-xs font-medium text-success-400">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex rounded-full bg-bgray-100 px-2 py-1 text-xs font-medium text-bgray-600">
                                                    Selesai
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <i class="fa fa-history mb-4 text-4xl text-bgray-300"></i>
                        <p class="text-bgray-500 dark:text-bgray-50">Belum ada riwayat pemakai kendaraan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="title">Detail Merk</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('merk.index') }}"
                    class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">{{ $merk->nama }}</h2>
                    <p class="text-sm text-bgray-600 dark:text-bgray-50">
                        @if($merk->jenis === 'mobil')
                            <i class="fa fa-car mr-1"></i> Merk Mobil
                        @else
                            <i class="fa fa-motorcycle mr-1"></i> Merk Motor
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('merk.edit', $merk) }}"
                    class="inline-flex items-center rounded-lg border border-bgray-300 px-4 py-2 font-semibold text-bgray-600 transition-all hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    <i class="fa fa-edit mr-2"></i> Edit
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Stats Card -->
    <div class="mb-6 rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-bgray-500 dark:text-bgray-50">Total Kendaraan dengan Merk Ini</p>
                <p class="text-3xl font-bold text-bgray-900 dark:text-white">{{ $merk->kendaraan_count }}</p>
            </div>
            <div class="rounded-full bg-success-50 p-4">
                @if($merk->jenis === 'mobil')
                    <i class="fa fa-car text-2xl text-success-400"></i>
                @else
                    <i class="fa fa-motorcycle text-2xl text-success-400"></i>
                @endif
            </div>
        </div>
    </div>

    <!-- Kendaraan List -->
    <div class="rounded-lg bg-white dark:bg-darkblack-600">
        <div class="flex items-center justify-between border-b border-bgray-200 p-6 dark:border-darkblack-400">
            <h3 class="text-lg font-semibold text-bgray-900 dark:text-white">
                Daftar Kendaraan
            </h3>
            <a href="{{ route('kendaraan.create', ['merk_id' => $merk->id]) }}"
                class="text-sm font-semibold text-success-300 hover:underline">
                <i class="fa fa-plus mr-1"></i> Tambah Kendaraan
            </a>
        </div>

        @if($merk->kendaraan->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-bgray-200 dark:border-darkblack-400">
                        <tr class="text-left">
                            <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Model</th>
                            <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Plat Nomor</th>
                            <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Tahun</th>
                            <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Garasi</th>
                            <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($merk->kendaraan as $k)
                            <tr class="border-b border-bgray-200 last:border-0 dark:border-darkblack-400">
                                <td class="px-6 py-4">
                                    <a href="{{ route('kendaraan.show', $k) }}" class="font-medium text-bgray-900 hover:text-success-300 dark:text-white">
                                        {{ $k->nama_model }}
                                    </a>
                                    <p class="text-sm text-bgray-500 dark:text-bgray-50">{{ $k->warna }}</p>
                                </td>
                                <td class="px-6 py-4 font-mono text-bgray-900 dark:text-white">
                                    {{ $k->plat_nomor }}
                                </td>
                                <td class="px-6 py-4 text-bgray-900 dark:text-white">
                                    {{ $k->tahun_pembuatan }}
                                </td>
                                <td class="px-6 py-4 text-bgray-900 dark:text-white">
                                    {{ $k->garasi->nama ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($k->status === 'aktif')
                                        <span class="inline-flex rounded-full bg-success-50 px-2 py-1 text-xs font-medium text-success-400">
                                            Aktif
                                        </span>
                                    @elseif($k->status === 'nonaktif')
                                        <span class="inline-flex rounded-full bg-warning-50 px-2 py-1 text-xs font-medium text-warning-400">
                                            Non-Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-bgray-100 px-2 py-1 text-xs font-medium text-bgray-600">
                                            Dihibahkan
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($merk->kendaraan_count > 20)
                <div class="border-t border-bgray-200 p-4 text-center dark:border-darkblack-400">
                    <a href="{{ route('kendaraan.index', ['merk_id' => $merk->id]) }}"
                        class="text-sm font-semibold text-success-300 hover:underline">
                        Lihat semua {{ $merk->kendaraan_count }} kendaraan
                    </a>
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <i class="fa fa-car mb-4 text-4xl text-bgray-300"></i>
                <p class="text-bgray-500 dark:text-bgray-50">Belum ada kendaraan dengan merk ini</p>
                <a href="{{ route('kendaraan.create', ['merk_id' => $merk->id]) }}"
                    class="mt-2 inline-block text-success-300 hover:underline">
                    Tambah kendaraan pertama
                </a>
            </div>
        @endif
    </div>
</x-app-layout>

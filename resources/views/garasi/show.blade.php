<x-app-layout>
    <x-slot name="title">Detail Garasi</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('garasi.index') }}"
                    class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">{{ $garasi->nama }}</h2>
                    <p class="text-sm text-bgray-600 dark:text-bgray-50">{{ $garasi->kevikepan->nama ?? '-' }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('garasi.edit', $garasi) }}"
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
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Informasi Garasi</h3>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Alamat</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $garasi->alamat }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Kota</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $garasi->kota }}</dd>
                    </div>
                    @if($garasi->kode_pos)
                        <div>
                            <dt class="text-sm text-bgray-500 dark:text-bgray-50">Kode Pos</dt>
                            <dd class="text-bgray-900 dark:text-white">{{ $garasi->kode_pos }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Kevikepan</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $garasi->kevikepan->nama ?? '-' }}</dd>
                    </div>
                </dl>

                @if($garasi->pic_name)
                    <hr class="my-4 border-bgray-200 dark:border-darkblack-400">
                    <h4 class="mb-3 font-semibold text-bgray-900 dark:text-white">Person In Charge</h4>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm text-bgray-500 dark:text-bgray-50">Nama</dt>
                            <dd class="text-bgray-900 dark:text-white">{{ $garasi->pic_name }}</dd>
                        </div>
                        @if($garasi->pic_phone)
                            <div>
                                <dt class="text-sm text-bgray-500 dark:text-bgray-50">Telepon</dt>
                                <dd>
                                    <a href="tel:{{ $garasi->pic_phone }}" class="text-success-300 hover:underline">
                                        {{ $garasi->pic_phone }}
                                    </a>
                                </dd>
                            </div>
                        @endif
                    </dl>
                @endif

                <hr class="my-4 border-bgray-200 dark:border-darkblack-400">
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-bgray-500 dark:text-bgray-50">Dibuat</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $garasi->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-bgray-500 dark:text-bgray-50">Diperbarui</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $garasi->updated_at->format('d M Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Kendaraan List -->
        <div class="lg:col-span-2">
            <div class="rounded-lg bg-white dark:bg-darkblack-600">
                <div class="flex items-center justify-between border-b border-bgray-200 p-6 dark:border-darkblack-400">
                    <h3 class="text-lg font-semibold text-bgray-900 dark:text-white">
                        Daftar Kendaraan
                        <span class="ml-2 rounded-full bg-success-50 px-2 py-1 text-sm text-success-400">
                            {{ $garasi->kendaraan->count() }} unit
                        </span>
                    </h3>
                    <a href="{{ route('kendaraan.create', ['garasi_id' => $garasi->id]) }}"
                        class="text-sm font-semibold text-success-300 hover:underline">
                        <i class="fa fa-plus mr-1"></i> Tambah Kendaraan
                    </a>
                </div>

                @if($garasi->kendaraan->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b border-bgray-200 dark:border-darkblack-400">
                                <tr class="text-left">
                                    <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Kendaraan</th>
                                    <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Plat Nomor</th>
                                    <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Pemegang</th>
                                    <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($garasi->kendaraan as $k)
                                    <tr class="border-b border-bgray-200 last:border-0 dark:border-darkblack-400">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('kendaraan.show', $k) }}" class="font-medium text-bgray-900 hover:text-success-300 dark:text-white">
                                                {{ $k->merk->nama ?? '' }} {{ $k->nama_model }}
                                            </a>
                                            <p class="text-sm text-bgray-500 dark:text-bgray-50">
                                                {{ ucfirst($k->jenis) }} - {{ $k->tahun_pembuatan }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-bgray-900 dark:text-white">
                                            {{ $k->plat_nomor }}
                                        </td>
                                        <td class="px-6 py-4 text-bgray-900 dark:text-white">
                                            {{ $k->pemegang->name ?? '-' }}
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
                @else
                    <div class="p-12 text-center">
                        <i class="fa fa-car mb-4 text-4xl text-bgray-300"></i>
                        <p class="text-bgray-500 dark:text-bgray-50">Belum ada kendaraan di garasi ini</p>
                        <a href="{{ route('kendaraan.create', ['garasi_id' => $garasi->id]) }}"
                            class="mt-2 inline-block text-success-300 hover:underline">
                            Tambah kendaraan pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

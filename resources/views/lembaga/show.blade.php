<x-app-layout>
    <x-slot name="title">Detail Lembaga</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('lembaga.index') }}"
                    class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">{{ $lembaga->nama }}</h2>
                    <p class="text-sm text-bgray-600 dark:text-bgray-50">{{ $lembaga->kota ?? 'Lembaga' }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('lembaga.edit', $lembaga) }}"
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
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Informasi Lembaga</h3>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Status</dt>
                        <dd>
                            @if($lembaga->is_active)
                                <span class="inline-flex rounded-full bg-accent-50 px-2 py-1 text-xs font-medium text-accent-400">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-bgray-100 px-2 py-1 text-xs font-medium text-bgray-600">
                                    Tidak Aktif
                                </span>
                            @endif
                        </dd>
                    </div>
                    @if($lembaga->alamat)
                        <div>
                            <dt class="text-sm text-bgray-500 dark:text-bgray-50">Alamat</dt>
                            <dd class="text-bgray-900 dark:text-white">{{ $lembaga->alamat }}</dd>
                        </div>
                    @endif
                    @if($lembaga->kota)
                        <div>
                            <dt class="text-sm text-bgray-500 dark:text-bgray-50">Kota</dt>
                            <dd class="text-bgray-900 dark:text-white">{{ $lembaga->kota }}</dd>
                        </div>
                    @endif
                    @if($lembaga->telepon)
                        <div>
                            <dt class="text-sm text-bgray-500 dark:text-bgray-50">Telepon</dt>
                            <dd>
                                <a href="tel:{{ $lembaga->telepon }}" class="text-accent-300 hover:underline">
                                    {{ $lembaga->telepon }}
                                </a>
                            </dd>
                        </div>
                    @endif
                    @if($lembaga->email)
                        <div>
                            <dt class="text-sm text-bgray-500 dark:text-bgray-50">Email</dt>
                            <dd>
                                <a href="mailto:{{ $lembaga->email }}" class="text-accent-300 hover:underline">
                                    {{ $lembaga->email }}
                                </a>
                            </dd>
                        </div>
                    @endif
                </dl>

                <hr class="my-4 border-bgray-200 dark:border-darkblack-400">
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-bgray-500 dark:text-bgray-50">Dibuat</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $lembaga->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-bgray-500 dark:text-bgray-50">Diperbarui</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $lembaga->updated_at->format('d M Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Kendaraan & Riwayat Pemakai -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Kendaraan Dimiliki -->
            <div class="rounded-lg bg-white dark:bg-darkblack-600">
                <div class="flex items-center justify-between border-b border-bgray-200 p-6 dark:border-darkblack-400">
                    <h3 class="text-lg font-semibold text-bgray-900 dark:text-white">
                        Kendaraan Dimiliki
                        <span class="ml-2 rounded-full bg-accent-50 px-2 py-1 text-sm text-accent-400">
                            {{ $lembaga->kendaraanDimiliki->count() }} unit
                        </span>
                    </h3>
                </div>

                @if($lembaga->kendaraanDimiliki->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b border-bgray-200 dark:border-darkblack-400">
                                <tr class="text-left">
                                    <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Kendaraan</th>
                                    <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Plat Nomor</th>
                                    <th class="px-6 py-3 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lembaga->kendaraanDimiliki as $k)
                                    <tr class="border-b border-bgray-200 last:border-0 dark:border-darkblack-400">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('kendaraan.show', $k) }}" class="font-medium text-bgray-900 hover:text-accent-300 dark:text-white">
                                                {{ $k->merk->nama ?? '' }} {{ $k->nama_model }}
                                            </a>
                                            <p class="text-sm text-bgray-500 dark:text-bgray-50">
                                                {{ ucfirst($k->jenis) }} - {{ $k->tahun_pembuatan }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-bgray-900 dark:text-white">
                                            {{ $k->plat_nomor }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($k->status === 'aktif')
                                                <span class="inline-flex rounded-full bg-accent-50 px-2 py-1 text-xs font-medium text-accent-400">
                                                    Aktif
                                                </span>
                                            @elseif($k->status === 'nonaktif')
                                                <span class="inline-flex rounded-full bg-warning-50 px-2 py-1 text-xs font-medium text-warning-400">
                                                    Non-Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex rounded-full bg-bgray-100 px-2 py-1 text-xs font-medium text-bgray-600">
                                                    {{ ucfirst($k->status) }}
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
                        <p class="text-bgray-500 dark:text-bgray-50">Belum ada kendaraan yang dimiliki lembaga ini</p>
                    </div>
                @endif
            </div>

            <!-- Riwayat Pemakai -->
            <div class="rounded-lg bg-white dark:bg-darkblack-600">
                <div class="flex items-center justify-between border-b border-bgray-200 p-6 dark:border-darkblack-400">
                    <h3 class="text-lg font-semibold text-bgray-900 dark:text-white">
                        Riwayat Pemakai Kendaraan
                        <span class="ml-2 rounded-full bg-accent-50 px-2 py-1 text-sm text-accent-400">
                            {{ $lembaga->riwayatPemakai->count() }} data
                        </span>
                    </h3>
                </div>

                @if($lembaga->riwayatPemakai->count() > 0)
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
                                @foreach($lembaga->riwayatPemakai as $riwayat)
                                    <tr class="border-b border-bgray-200 last:border-0 dark:border-darkblack-400">
                                        <td class="px-6 py-4">
                                            @if($riwayat->kendaraan)
                                                <a href="{{ route('kendaraan.show', $riwayat->kendaraan) }}" class="font-medium text-bgray-900 hover:text-accent-300 dark:text-white">
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
                                                <span class="inline-flex rounded-full bg-accent-50 px-2 py-1 text-xs font-medium text-accent-400">
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

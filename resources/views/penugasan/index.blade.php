<x-app-layout>
    <x-slot name="title">Daftar Penugasan</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Daftar Penugasan Kendaraan</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">Kelola penugasan kendaraan ke pemegang</p>
            </div>
            <a href="{{ route('penugasan.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-success-300 px-4 py-3 font-semibold text-white transition-all hover:bg-success-400">
                <i class="fa fa-plus mr-2"></i> Tambah Penugasan
            </a>
        </div>
    </x-slot>

    <!-- Stats Cards -->
    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        <div class="rounded-lg bg-white p-4 dark:bg-darkblack-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-bgray-500 dark:text-bgray-50">Total Penugasan</p>
                    <p class="text-2xl font-bold text-bgray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-full bg-bgray-100 p-3 dark:bg-darkblack-500">
                    <i class="fa fa-users text-bgray-600"></i>
                </div>
            </div>
        </div>
        <div class="rounded-lg bg-white p-4 dark:bg-darkblack-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-bgray-500 dark:text-bgray-50">Aktif</p>
                    <p class="text-2xl font-bold text-success-400">{{ $stats['aktif'] }}</p>
                </div>
                <div class="rounded-full bg-success-50 p-3">
                    <i class="fa fa-play-circle text-success-400"></i>
                </div>
            </div>
        </div>
        <div class="rounded-lg bg-white p-4 dark:bg-darkblack-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-bgray-500 dark:text-bgray-50">Selesai</p>
                    <p class="text-2xl font-bold text-blue-500">{{ $stats['selesai'] }}</p>
                </div>
                <div class="rounded-full bg-blue-50 p-3">
                    <i class="fa fa-check-circle text-blue-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="mb-6 rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="GET" action="{{ route('penugasan.index') }}" class="grid gap-4 md:grid-cols-5">
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Plat nomor, pemegang, tujuan..."
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Kendaraan</label>
                <select name="kendaraan_id"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua</option>
                    @foreach($kendaraan as $k)
                        <option value="{{ $k->id }}" {{ request('kendaraan_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->plat_nomor }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Pemegang</label>
                <select name="pemegang_id"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua</option>
                    @foreach($pemegang as $p)
                        <option value="{{ $p->id }}" {{ request('pemegang_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Status</label>
                <select name="status"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="rounded-lg bg-success-300 px-4 py-3 font-semibold text-white transition-all hover:bg-success-400">
                    <i class="fa fa-search"></i>
                </button>
                <a href="{{ route('penugasan.index') }}"
                    class="rounded-lg border border-bgray-300 px-4 py-3 text-bgray-600 hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="rounded-lg bg-white dark:bg-darkblack-600">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-bgray-200 dark:border-darkblack-400">
                    <tr class="text-left">
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Kendaraan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Pemegang</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Periode</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Tujuan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Status</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-bgray-600 dark:text-bgray-50">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penugasan as $p)
                        <tr class="border-b border-bgray-200 last:border-0 dark:border-darkblack-400">
                            <td class="px-6 py-4">
                                <a href="{{ route('kendaraan.show', $p->kendaraan) }}" class="font-medium text-bgray-900 hover:text-success-300 dark:text-white">
                                    {{ $p->kendaraan->plat_nomor }}
                                </a>
                                <p class="text-sm text-bgray-500 dark:text-bgray-50">
                                    {{ $p->kendaraan->merk->nama ?? '' }} {{ $p->kendaraan->nama_model }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-bgray-900 dark:text-white">{{ $p->pemegang->name }}</span>
                                <p class="text-sm text-bgray-500 dark:text-bgray-50">{{ $p->pemegang->lembaga ?? $p->pemegang->type }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-bgray-900 dark:text-white">{{ $p->tanggal_mulai->format('d M Y') }}</span>
                                @if($p->tanggal_selesai)
                                    <p class="text-xs text-bgray-500">s/d {{ $p->tanggal_selesai->format('d M Y') }}</p>
                                @elseif($p->isAktif())
                                    <p class="text-xs text-success-400">{{ $p->durasi }} hari berjalan</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-bgray-900 dark:text-white">
                                {{ Str::limit($p->tujuan, 30) ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($p->status === 'aktif')
                                    <span class="inline-flex rounded-full bg-success-50 px-2 py-1 text-xs font-medium text-success-400">
                                        <i class="fa fa-play-circle mr-1"></i> Aktif
                                    </span>
                                @elseif($p->status === 'selesai')
                                    <span class="inline-flex rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-600">
                                        <i class="fa fa-check-circle mr-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-bgray-100 px-2 py-1 text-xs font-medium text-bgray-600">
                                        <i class="fa fa-times-circle mr-1"></i> Dibatalkan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('penugasan.show', $p) }}"
                                        class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500"
                                        title="Detail">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @if($p->isAktif())
                                        <a href="{{ route('penugasan.edit', $p) }}"
                                            class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500"
                                            title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-bgray-500 dark:text-bgray-50">
                                <i class="fa fa-users mb-4 text-4xl text-bgray-300"></i>
                                <p>Belum ada data penugasan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($penugasan->hasPages())
            <div class="border-t border-bgray-200 px-6 py-4 dark:border-darkblack-400">
                {{ $penugasan->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

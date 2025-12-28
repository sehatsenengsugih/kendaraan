<x-app-layout>
    <x-slot name="title">Daftar Pajak</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Daftar Pajak Kendaraan</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">Kelola pajak tahunan dan 5 tahunan</p>
            </div>
            <a href="{{ route('pajak.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-accent-300 px-4 py-3 font-semibold text-white transition-all hover:bg-accent-400">
                <i class="fa fa-plus mr-2"></i> Tambah Pajak
            </a>
        </div>
    </x-slot>

    <!-- Stats Cards -->
    <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg bg-white p-4 dark:bg-darkblack-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-bgray-500 dark:text-bgray-50">Total Pajak</p>
                    <p class="text-2xl font-bold text-bgray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-full bg-bgray-100 p-3 dark:bg-darkblack-500">
                    <i class="fa fa-file-invoice text-bgray-600"></i>
                </div>
            </div>
        </div>
        <div class="rounded-lg bg-white p-4 dark:bg-darkblack-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-bgray-500 dark:text-bgray-50">Belum Bayar</p>
                    <p class="text-2xl font-bold text-warning-400">{{ $stats['belum_bayar'] }}</p>
                </div>
                <div class="rounded-full bg-warning-50 p-3">
                    <i class="fa fa-clock text-warning-400"></i>
                </div>
            </div>
        </div>
        <div class="rounded-lg bg-white p-4 dark:bg-darkblack-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-bgray-500 dark:text-bgray-50">Jatuh Tempo 30 Hari</p>
                    <p class="text-2xl font-bold text-orange-500">{{ $stats['due_soon'] }}</p>
                </div>
                <div class="rounded-full bg-orange-50 p-3">
                    <i class="fa fa-exclamation-triangle text-orange-500"></i>
                </div>
            </div>
        </div>
        <div class="rounded-lg bg-white p-4 dark:bg-darkblack-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-bgray-500 dark:text-bgray-50">Terlambat</p>
                    <p class="text-2xl font-bold text-error-300">{{ $stats['terlambat'] }}</p>
                </div>
                <div class="rounded-full bg-error-50 p-3">
                    <i class="fa fa-times-circle text-error-300"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="mb-6 rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="GET" action="{{ route('pajak.index') }}" class="grid gap-4 md:grid-cols-6">
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Plat nomor..."
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Status Pajak</label>
                <select name="status"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua</option>
                    <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Jenis Pajak</label>
                <select name="jenis"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua</option>
                    <option value="tahunan" {{ request('jenis') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                    <option value="lima_tahunan" {{ request('jenis') == 'lima_tahunan' ? 'selected' : '' }}>5 Tahunan</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Status Kendaraan</label>
                <select name="status_kendaraan"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua</option>
                    <option value="aktif" {{ request('status_kendaraan') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status_kendaraan') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                    <option value="dijual" {{ request('status_kendaraan') == 'dijual' ? 'selected' : '' }}>Dijual</option>
                    <option value="dihibahkan" {{ request('status_kendaraan') == 'dihibahkan' ? 'selected' : '' }}>Dihibahkan</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Filter Cepat</label>
                <div class="flex gap-2 py-3">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="due_soon" value="1" {{ request('due_soon') ? 'checked' : '' }}
                            class="rounded border-bgray-300 text-accent-300 focus:ring-accent-300">
                        <span class="text-sm text-bgray-600 dark:text-bgray-50">Jatuh Tempo</span>
                    </label>
                </div>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="rounded-lg bg-accent-300 px-4 py-3 font-semibold text-white transition-all hover:bg-accent-400">
                    <i class="fa fa-search"></i>
                </button>
                <a href="{{ route('pajak.index') }}"
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
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">
                            <x-sortable-header column="jenis" label="Jenis" :currentSort="$sortColumn" :currentDirection="$sortDirection" />
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">
                            <x-sortable-header column="tanggal_jatuh_tempo" label="Jatuh Tempo" :currentSort="$sortColumn" :currentDirection="$sortDirection" />
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">
                            <x-sortable-header column="nominal" label="Nominal" :currentSort="$sortColumn" :currentDirection="$sortDirection" />
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">
                            <x-sortable-header column="status" label="Status" :currentSort="$sortColumn" :currentDirection="$sortDirection" />
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-bgray-600 dark:text-bgray-50">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pajak as $p)
                        <tr class="border-b border-bgray-200 last:border-0 dark:border-darkblack-400 {{ $p->isOverdue() ? 'bg-error-50/30' : ($p->isDueSoon() ? 'bg-warning-50/30' : '') }}">
                            <td class="px-6 py-4">
                                <a href="{{ route('kendaraan.show', $p->kendaraan) }}" class="font-medium text-bgray-900 hover:text-accent-300 dark:text-white">
                                    {{ $p->kendaraan->plat_nomor }}
                                </a>
                                <p class="text-sm text-bgray-500 dark:text-bgray-50">
                                    {{ $p->kendaraan->merk->nama ?? '' }} {{ $p->kendaraan->nama_model }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                @if($p->jenis === 'tahunan')
                                    <span class="inline-flex rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-600">
                                        Tahunan
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-purple-50 px-2 py-1 text-xs font-medium text-purple-600">
                                        5 Tahunan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-bgray-900 dark:text-white">{{ $p->tanggal_jatuh_tempo->format('d M Y') }}</span>
                                @if($p->status !== 'lunas')
                                    @if($p->days_until_due < 0)
                                        <p class="text-xs text-error-300">{{ abs($p->days_until_due) }} hari terlambat</p>
                                    @elseif($p->days_until_due <= 30)
                                        <p class="text-xs text-warning-400">{{ $p->days_until_due }} hari lagi</p>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 text-bgray-900 dark:text-white">
                                @if($p->nominal)
                                    Rp {{ number_format($p->nominal, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($p->status === 'lunas')
                                    <span class="inline-flex rounded-full bg-accent-50 px-2 py-1 text-xs font-medium text-accent-400">
                                        <i class="fa fa-check mr-1"></i> Lunas
                                    </span>
                                @elseif($p->isOverdue())
                                    <span class="inline-flex rounded-full bg-error-50 px-2 py-1 text-xs font-medium text-error-300">
                                        <i class="fa fa-times mr-1"></i> Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-warning-50 px-2 py-1 text-xs font-medium text-warning-400">
                                        <i class="fa fa-clock mr-1"></i> Belum Bayar
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('pajak.show', $p) }}"
                                        class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500"
                                        title="Detail">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @if($p->status !== 'lunas')
                                        <a href="{{ route('pajak.edit', $p) }}"
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
                                <i class="fa fa-file-invoice mb-4 text-4xl text-bgray-300"></i>
                                <p>Belum ada data pajak</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pajak->hasPages())
            <div class="border-t border-bgray-200 px-6 py-4 dark:border-darkblack-400">
                {{ $pajak->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

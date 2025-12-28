<x-app-layout>
    <x-slot name="title">Daftar Servis</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Daftar Servis Kendaraan</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">Kelola riwayat dan jadwal servis</p>
            </div>
            <a href="{{ route('servis.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-accent-300 px-4 py-3 font-semibold text-white transition-all hover:bg-accent-400">
                <i class="fa fa-plus mr-2"></i> Tambah Servis
            </a>
        </div>
    </x-slot>

    <!-- Stats Cards -->
    <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="rounded-lg bg-white p-4 dark:bg-darkblack-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-bgray-500 dark:text-bgray-50">Total Servis</p>
                    <p class="text-2xl font-bold text-bgray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-full bg-bgray-100 p-3 dark:bg-darkblack-500">
                    <i class="fa fa-wrench text-bgray-600"></i>
                </div>
            </div>
        </div>
        <div class="rounded-lg bg-white p-4 dark:bg-darkblack-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-bgray-500 dark:text-bgray-50">Dijadwalkan</p>
                    <p class="text-2xl font-bold text-blue-500">{{ $stats['dijadwalkan'] }}</p>
                </div>
                <div class="rounded-full bg-blue-50 p-3">
                    <i class="fa fa-calendar-alt text-blue-500"></i>
                </div>
            </div>
        </div>
        <div class="rounded-lg bg-white p-4 dark:bg-darkblack-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-bgray-500 dark:text-bgray-50">Dalam Proses</p>
                    <p class="text-2xl font-bold text-warning-400">{{ $stats['dalam_proses'] }}</p>
                </div>
                <div class="rounded-full bg-warning-50 p-3">
                    <i class="fa fa-tools text-warning-400"></i>
                </div>
            </div>
        </div>
        <div class="rounded-lg bg-white p-4 dark:bg-darkblack-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-bgray-500 dark:text-bgray-50">Selesai (Bulan Ini)</p>
                    <p class="text-2xl font-bold text-accent-400">{{ $stats['selesai_bulan_ini'] }}</p>
                </div>
                <div class="rounded-full bg-accent-50 p-3">
                    <i class="fa fa-check-circle text-accent-400"></i>
                </div>
            </div>
        </div>
        <div class="rounded-lg bg-white p-4 dark:bg-darkblack-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-bgray-500 dark:text-bgray-50">Biaya (Bulan Ini)</p>
                    <p class="text-lg font-bold text-bgray-900 dark:text-white">Rp {{ number_format($stats['total_biaya_bulan_ini'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-full bg-purple-50 p-3">
                    <i class="fa fa-money-bill-wave text-purple-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="mb-6 rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="GET" action="{{ route('servis.index') }}" class="grid gap-4 md:grid-cols-5">
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Deskripsi, bengkel, plat..."
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Kendaraan</label>
                <select name="kendaraan_id"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua</option>
                    @foreach($kendaraan as $k)
                        <option value="{{ $k->id }}" {{ request('kendaraan_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->plat_nomor }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Status</label>
                <select name="status"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua</option>
                    <option value="dijadwalkan" {{ request('status') == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                    <option value="dalam_proses" {{ request('status') == 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Jenis</label>
                <select name="jenis"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua</option>
                    <option value="rutin" {{ request('jenis') == 'rutin' ? 'selected' : '' }}>Servis Rutin</option>
                    <option value="perbaikan" {{ request('jenis') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                    <option value="darurat" {{ request('jenis') == 'darurat' ? 'selected' : '' }}>Perbaikan Darurat</option>
                    <option value="overhaul" {{ request('jenis') == 'overhaul' ? 'selected' : '' }}>Overhaul</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="rounded-lg bg-accent-300 px-4 py-3 font-semibold text-white transition-all hover:bg-accent-400">
                    <i class="fa fa-search"></i>
                </button>
                <a href="{{ route('servis.index') }}"
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
                            <x-sortable-header column="tanggal_servis" label="Tanggal" :currentSort="$sortColumn" :currentDirection="$sortDirection" />
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Bengkel</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">
                            <x-sortable-header column="biaya" label="Biaya" :currentSort="$sortColumn" :currentDirection="$sortDirection" />
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">
                            <x-sortable-header column="status" label="Status" :currentSort="$sortColumn" :currentDirection="$sortDirection" />
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-bgray-600 dark:text-bgray-50">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servis as $s)
                        <tr class="border-b border-bgray-200 last:border-0 dark:border-darkblack-400">
                            <td class="px-6 py-4">
                                <a href="{{ route('kendaraan.show', $s->kendaraan) }}" class="font-medium text-bgray-900 hover:text-accent-300 dark:text-white">
                                    {{ $s->kendaraan->plat_nomor }}
                                </a>
                                <p class="text-sm text-bgray-500 dark:text-bgray-50">
                                    {{ $s->kendaraan->merk->nama ?? '' }} {{ $s->kendaraan->nama_model }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                @if($s->jenis === 'rutin')
                                    <span class="inline-flex rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-600">
                                        Servis Rutin
                                    </span>
                                @elseif($s->jenis === 'perbaikan')
                                    <span class="inline-flex rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-600">
                                        Perbaikan
                                    </span>
                                @elseif($s->jenis === 'darurat')
                                    <span class="inline-flex rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-600">
                                        Darurat
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-purple-50 px-2 py-1 text-xs font-medium text-purple-600">
                                        Overhaul
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-bgray-900 dark:text-white">{{ $s->tanggal_servis->format('d M Y') }}</span>
                                @if($s->tanggal_selesai)
                                    <p class="text-xs text-bgray-500">s/d {{ $s->tanggal_selesai->format('d M Y') }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-bgray-900 dark:text-white">
                                {{ $s->bengkel ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-bgray-900 dark:text-white">
                                @if($s->biaya)
                                    {{ $s->biaya_formatted }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($s->status === 'selesai')
                                    <span class="inline-flex rounded-full bg-accent-50 px-2 py-1 text-xs font-medium text-accent-400">
                                        <i class="fa fa-check mr-1"></i> Selesai
                                    </span>
                                @elseif($s->status === 'dalam_proses')
                                    <span class="inline-flex rounded-full bg-warning-50 px-2 py-1 text-xs font-medium text-warning-400">
                                        <i class="fa fa-tools mr-1"></i> Dalam Proses
                                    </span>
                                @elseif($s->status === 'dijadwalkan')
                                    <span class="inline-flex rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-600">
                                        <i class="fa fa-calendar mr-1"></i> Dijadwalkan
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-bgray-100 px-2 py-1 text-xs font-medium text-bgray-600">
                                        <i class="fa fa-times mr-1"></i> Dibatalkan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('servis.show', $s) }}"
                                        class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500"
                                        title="Detail">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @if(!$s->isSelesai())
                                        <a href="{{ route('servis.edit', $s) }}"
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
                            <td colspan="7" class="px-6 py-12 text-center text-bgray-500 dark:text-bgray-50">
                                <i class="fa fa-wrench mb-4 text-4xl text-bgray-300"></i>
                                <p>Belum ada data servis</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($servis->hasPages())
            <div class="border-t border-bgray-200 px-6 py-4 dark:border-darkblack-400">
                {{ $servis->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

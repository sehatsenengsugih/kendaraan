<x-app-layout>
    <x-slot name="title">Daftar Kendaraan</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Daftar Kendaraan</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">Kelola semua kendaraan Keuskupan Agung Semarang</p>
            </div>
            <a href="{{ route('kendaraan.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-success-300 px-4 py-3 font-semibold text-white transition-all hover:bg-success-400">
                <i class="fa fa-plus mr-2"></i> Tambah Kendaraan
            </a>
        </div>
    </x-slot>

    <!-- Filter Card -->
    <div class="mb-6 rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="GET" action="{{ route('kendaraan.index') }}">
            <!-- Baris 1: Search -->
            <div class="mb-4">
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                    <i class="fa fa-search mr-1 text-bgray-500"></i> Pencarian Lengkap
                </label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari plat nomor, model, merk, no. BPKB, no. rangka, no. mesin, pemegang, garasi, catatan..."
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                <p class="mt-1 text-xs text-bgray-500 dark:text-bgray-400">
                    Cari di semua field: identitas, dokumen, pemegang, garasi, riwayat pemakai, dll.
                </p>
            </div>

            <!-- Baris 2: Filter -->
            <div class="grid gap-4 md:grid-cols-5">
                <div>
                    <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Jenis</label>
                    <select name="jenis"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        <option value="">Semua Jenis</option>
                        <option value="mobil" {{ request('jenis') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                        <option value="motor" {{ request('jenis') == 'motor' ? 'selected' : '' }}>Motor</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Merk</label>
                    <select name="merk_id"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        <option value="">Semua Merk</option>
                        @foreach($merk as $m)
                            <option value="{{ $m->id }}" {{ request('merk_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Garasi</label>
                    <select name="garasi_id"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        <option value="">Semua Garasi</option>
                        @foreach($garasi as $g)
                            <option value="{{ $g->id }}" {{ request('garasi_id') == $g->id ? 'selected' : '' }}>
                                {{ $g->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Status</label>
                    <select name="status"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        <option value="dihibahkan" {{ request('status') == 'dihibahkan' ? 'selected' : '' }}>Dihibahkan</option>
                        <option value="dijual" {{ request('status') == 'dijual' ? 'selected' : '' }}>Dijual</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 rounded-lg bg-success-300 px-4 py-3 font-semibold text-white transition-all hover:bg-success-400">
                        <i class="fa fa-search mr-1"></i> Cari
                    </button>
                    <a href="{{ route('kendaraan.index') }}"
                        class="rounded-lg border border-bgray-300 px-4 py-3 text-bgray-600 transition-all hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50"
                        title="Reset Filter">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>

            <!-- Active Filters Badge -->
            @if(request()->hasAny(['search', 'jenis', 'merk_id', 'garasi_id', 'status']))
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <span class="text-sm text-bgray-600 dark:text-bgray-300">Filter aktif:</span>
                @if(request('search'))
                    <span class="inline-flex items-center gap-1 rounded-full bg-success-50 px-3 py-1 text-xs font-medium text-success-400">
                        <i class="fa fa-search"></i> "{{ request('search') }}"
                    </span>
                @endif
                @if(request('jenis'))
                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-600">
                        {{ ucfirst(request('jenis')) }}
                    </span>
                @endif
                @if(request('merk_id'))
                    <span class="inline-flex items-center gap-1 rounded-full bg-purple-50 px-3 py-1 text-xs font-medium text-purple-600">
                        {{ $merk->find(request('merk_id'))->nama ?? '' }}
                    </span>
                @endif
                @if(request('garasi_id'))
                    <span class="inline-flex items-center gap-1 rounded-full bg-orange-50 px-3 py-1 text-xs font-medium text-orange-600">
                        {{ $garasi->find(request('garasi_id'))->nama ?? '' }}
                    </span>
                @endif
                @if(request('status'))
                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                        {{ ucfirst(request('status')) }}
                    </span>
                @endif
            </div>
            @endif
        </form>
    </div>

    <!-- Table Card -->
    <div class="rounded-lg bg-white dark:bg-darkblack-600">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-bgray-200 dark:border-darkblack-400">
                    <tr class="text-left">
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">
                            <x-sortable-header column="nama_model" label="Kendaraan" :currentSort="$sortColumn" :currentDirection="$sortDirection" />
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">
                            <x-sortable-header column="plat_nomor" label="Plat Nomor" :currentSort="$sortColumn" :currentDirection="$sortDirection" />
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Garasi</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Pemegang</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">
                            <x-sortable-header column="status" label="Status" :currentSort="$sortColumn" :currentDirection="$sortDirection" />
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-bgray-600 dark:text-bgray-50">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kendaraan as $k)
                        <tr class="border-b border-bgray-200 last:border-0 dark:border-darkblack-400">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-lg bg-bgray-100">
                                        @if($k->avatar_path)
                                            <img src="{{ asset('storage/' . $k->avatar_path) }}"
                                                alt="{{ $k->display_name }}"
                                                class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-bgray-400">
                                                <i class="fa {{ $k->jenis === 'motor' ? 'fa-motorcycle' : 'fa-car' }}"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('kendaraan.show', $k) }}" class="font-medium text-bgray-900 hover:text-success-300 dark:text-white">
                                            {{ $k->merk->nama ?? '' }} {{ $k->nama_model }}
                                        </a>
                                        <p class="text-sm text-bgray-500 dark:text-bgray-50">
                                            {{ ucfirst($k->jenis) }} - {{ $k->tahun_pembuatan }} - {{ $k->warna }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-bgray-900 dark:text-white">
                                {{ $k->plat_nomor }}
                            </td>
                            <td class="px-6 py-4 text-bgray-900 dark:text-white">
                                {{ $k->garasi->nama ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-bgray-900 dark:text-white">
                                {{ $k->pemegang_nama ?? $k->pemegang->name ?? '-' }}
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
                                @elseif($k->status === 'dijual')
                                    <span class="inline-flex rounded-full bg-error-50 px-2 py-1 text-xs font-medium text-error-300">
                                        Dijual
                                    </span>
                                @elseif($k->status === 'dihibahkan')
                                    <span class="inline-flex rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-600">
                                        Dihibahkan
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-bgray-100 px-2 py-1 text-xs font-medium text-bgray-600">
                                        {{ ucfirst($k->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('kendaraan.show', $k) }}"
                                        class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500"
                                        title="Lihat Detail">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('kendaraan.edit', $k) }}"
                                        class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500"
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('kendaraan.destroy', $k) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus kendaraan {{ $k->plat_nomor }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="rounded-lg p-2 text-error-300 hover:bg-error-50"
                                            title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-bgray-500 dark:text-bgray-50">
                                <i class="fa fa-car mb-4 text-4xl text-bgray-300"></i>
                                <p>Belum ada data kendaraan</p>
                                <a href="{{ route('kendaraan.create') }}" class="mt-2 inline-block text-success-300 hover:underline">
                                    Tambah kendaraan pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($kendaraan->hasPages())
            <div class="border-t border-bgray-200 px-6 py-4 dark:border-darkblack-400">
                {{ $kendaraan->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

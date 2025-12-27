<x-app-layout>
    <x-slot name="title">Daftar Garasi</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Daftar Garasi</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">Kelola lokasi penyimpanan kendaraan</p>
            </div>
            <a href="{{ route('garasi.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-success-300 px-4 py-3 font-semibold text-white transition-all hover:bg-success-400">
                <i class="fa fa-plus mr-2"></i> Tambah Garasi
            </a>
        </div>
    </x-slot>

    <!-- Filter Card -->
    <div class="mb-6 rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="GET" action="{{ route('garasi.index') }}" class="grid gap-4 md:grid-cols-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nama, alamat, kota..."
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Kevikepan</label>
                <select name="kevikepan_id"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua Kevikepan</option>
                    @foreach($kevikepan as $kev)
                        <option value="{{ $kev->id }}" {{ request('kevikepan_id') == $kev->id ? 'selected' : '' }}>
                            {{ $kev->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Kota</label>
                <select name="kota"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua Kota</option>
                    @foreach($kotaList as $kota)
                        <option value="{{ $kota }}" {{ request('kota') == $kota ? 'selected' : '' }}>
                            {{ $kota }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="rounded-lg bg-success-300 px-4 py-3 font-semibold text-white transition-all hover:bg-success-400">
                    <i class="fa fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('garasi.index') }}"
                    class="rounded-lg border border-bgray-300 px-4 py-3 font-semibold text-bgray-600 transition-all hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50 dark:hover:bg-darkblack-500">
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
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Nama Garasi</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Kevikepan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Kota</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">PIC</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-bgray-600 dark:text-bgray-50">Kendaraan</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-bgray-600 dark:text-bgray-50">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($garasi as $g)
                        <tr class="border-b border-bgray-200 last:border-0 dark:border-darkblack-400">
                            <td class="px-6 py-4">
                                <a href="{{ route('garasi.show', $g) }}" class="font-medium text-bgray-900 hover:text-success-300 dark:text-white">
                                    {{ $g->nama }}
                                </a>
                                <p class="text-sm text-bgray-500 dark:text-bgray-50">{{ Str::limit($g->alamat, 50) }}</p>
                            </td>
                            <td class="px-6 py-4 text-bgray-900 dark:text-white">
                                {{ $g->kevikepan->nama ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-bgray-900 dark:text-white">
                                {{ $g->kota }}
                            </td>
                            <td class="px-6 py-4">
                                @if($g->pic_name)
                                    <span class="text-bgray-900 dark:text-white">{{ $g->pic_name }}</span>
                                    @if($g->pic_phone)
                                        <p class="text-sm text-bgray-500 dark:text-bgray-50">{{ $g->pic_phone }}</p>
                                    @endif
                                @else
                                    <span class="text-bgray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center rounded-full bg-success-50 px-3 py-1 text-sm font-medium text-success-400">
                                    {{ $g->kendaraan_count }} unit
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('garasi.show', $g) }}"
                                        class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500"
                                        title="Lihat Detail">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('garasi.edit', $g) }}"
                                        class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500"
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('garasi.destroy', $g) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus garasi {{ $g->nama }}?')">
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
                                <i class="fa fa-warehouse mb-4 text-4xl text-bgray-300"></i>
                                <p>Belum ada data garasi</p>
                                <a href="{{ route('garasi.create') }}" class="mt-2 inline-block text-success-300 hover:underline">
                                    Tambah garasi pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($garasi->hasPages())
            <div class="border-t border-bgray-200 px-6 py-4 dark:border-darkblack-400">
                {{ $garasi->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

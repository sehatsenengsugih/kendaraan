<x-app-layout>
    <x-slot name="title">Daftar Merk</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Daftar Merk</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">Kelola merk kendaraan mobil dan motor</p>
            </div>
            <a href="{{ route('merk.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-success-300 px-4 py-3 font-semibold text-white transition-all hover:bg-success-400">
                <i class="fa fa-plus mr-2"></i> Tambah Merk
            </a>
        </div>
    </x-slot>

    <!-- Filter Card -->
    <div class="mb-6 rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="GET" action="{{ route('merk.index') }}" class="grid gap-4 md:grid-cols-3">
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nama merk..."
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Jenis</label>
                <select name="jenis"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua Jenis</option>
                    <option value="mobil" {{ request('jenis') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                    <option value="motor" {{ request('jenis') == 'motor' ? 'selected' : '' }}>Motor</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="rounded-lg bg-success-300 px-4 py-3 font-semibold text-white transition-all hover:bg-success-400">
                    <i class="fa fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('merk.index') }}"
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
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Nama Merk</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Jenis</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-bgray-600 dark:text-bgray-50">Jumlah Kendaraan</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-bgray-600 dark:text-bgray-50">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($merk as $m)
                        <tr class="border-b border-bgray-200 last:border-0 dark:border-darkblack-400">
                            <td class="px-6 py-4">
                                <a href="{{ route('merk.show', $m) }}" class="font-medium text-bgray-900 hover:text-success-300 dark:text-white">
                                    {{ $m->nama }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($m->jenis === 'mobil')
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-600">
                                        <i class="fa fa-car mr-1"></i> Mobil
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-orange-50 px-3 py-1 text-sm font-medium text-orange-600">
                                        <i class="fa fa-motorcycle mr-1"></i> Motor
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center rounded-full bg-success-50 px-3 py-1 text-sm font-medium text-success-400">
                                    {{ $m->kendaraan_count }} unit
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('merk.show', $m) }}"
                                        class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500"
                                        title="Lihat Detail">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('merk.edit', $m) }}"
                                        class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500"
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('merk.destroy', $m) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus merk {{ $m->nama }}?')">
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
                            <td colspan="4" class="px-6 py-12 text-center text-bgray-500 dark:text-bgray-50">
                                <i class="fa fa-tags mb-4 text-4xl text-bgray-300"></i>
                                <p>Belum ada data merk</p>
                                <a href="{{ route('merk.create') }}" class="mt-2 inline-block text-success-300 hover:underline">
                                    Tambah merk pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($merk->hasPages())
            <div class="border-t border-bgray-200 px-6 py-4 dark:border-darkblack-400">
                {{ $merk->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

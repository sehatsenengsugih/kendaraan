<x-app-layout>
    <x-slot name="title">Daftar Paroki</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Daftar Paroki</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">Kelola data master paroki</p>
            </div>
            <a href="{{ route('paroki.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-accent-300 px-4 py-3 font-semibold text-white transition-all hover:bg-accent-400">
                <i class="fa fa-plus mr-2"></i> Tambah Paroki
            </a>
        </div>
    </x-slot>

    <!-- Filter Card -->
    <div class="mb-6 rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="GET" action="{{ route('paroki.index') }}" class="grid gap-4 md:grid-cols-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nama, alamat, kota..."
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Kevikepan</label>
                <select name="kevikepan_id"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua Kevikepan</option>
                    @foreach($kevikepan as $kev)
                        <option value="{{ $kev->id }}" {{ request('kevikepan_id') == $kev->id ? 'selected' : '' }}>
                            {{ $kev->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Status</label>
                <select name="status"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="rounded-lg bg-accent-300 px-4 py-3 font-semibold text-white transition-all hover:bg-accent-400">
                    <i class="fa fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('paroki.index') }}"
                    class="rounded-lg border border-bgray-300 px-4 py-3 font-semibold text-bgray-600 transition-all hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-4">
        @forelse($paroki as $p)
            <div class="mobile-card bg-white rounded-xl p-4 shadow-sm dark:bg-darkblack-600">
                <div class="mobile-card-header">
                    <div class="flex-1">
                        <a href="{{ route('paroki.show', $p) }}" class="mobile-card-title text-bgray-900 hover:text-accent-300 dark:text-white">
                            {{ $p->nama }}
                        </a>
                        @if($p->alamat)
                            <p class="text-sm text-bgray-500 dark:text-bgray-400 mt-1">{{ Str::limit($p->alamat, 60) }}</p>
                        @endif
                    </div>
                    @if($p->is_active)
                        <span class="inline-flex items-center rounded-full bg-accent-50 px-2.5 py-1 text-xs font-medium text-accent-400">
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-bgray-100 px-2.5 py-1 text-xs font-medium text-bgray-600">
                            Non-aktif
                        </span>
                    @endif
                </div>

                <div class="mobile-card-body mt-3 space-y-2">
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Kevikepan</span>
                        <span class="mobile-card-value">{{ $p->kevikepan->nama ?? '-' }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Kota</span>
                        <span class="mobile-card-value">{{ $p->kota ?? '-' }}</span>
                    </div>
                    @if($p->telepon)
                    <div class="mobile-card-row">
                        <span class="mobile-card-label">Telepon</span>
                        <span class="mobile-card-value">{{ $p->telepon }}</span>
                    </div>
                    @endif
                </div>

                <div class="mobile-card-actions">
                    <a href="{{ route('paroki.show', $p) }}"
                        class="flex-1 inline-flex items-center justify-center rounded-lg bg-bgray-100 px-3 py-2.5 text-sm font-medium text-bgray-700 hover:bg-bgray-200 dark:bg-darkblack-500 dark:text-white dark:hover:bg-darkblack-400">
                        <i class="fa fa-eye mr-2"></i> Lihat
                    </a>
                    <a href="{{ route('paroki.edit', $p) }}"
                        class="flex-1 inline-flex items-center justify-center rounded-lg bg-accent-300 px-3 py-2.5 text-sm font-medium text-white hover:bg-accent-400">
                        <i class="fa fa-edit mr-2"></i> Edit
                    </a>
                </div>
            </div>
        @empty
            <div class="rounded-xl bg-white p-8 text-center dark:bg-darkblack-600">
                <i class="fa fa-church mb-4 text-4xl text-bgray-300"></i>
                <p class="text-bgray-500 dark:text-bgray-400">Belum ada data paroki</p>
                <a href="{{ route('paroki.create') }}" class="mt-2 inline-block text-accent-300 hover:underline">
                    Tambah paroki pertama
                </a>
            </div>
        @endforelse

        @if($paroki->hasPages())
            <div class="mt-4">
                {{ $paroki->links() }}
            </div>
        @endif
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block rounded-lg bg-white dark:bg-darkblack-600">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-bgray-200 dark:border-darkblack-400">
                    <tr class="text-left">
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">
                            <x-sortable-header column="nama" label="Nama Paroki" :currentSort="$sortColumn" :currentDirection="$sortDirection" />
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Kevikepan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">
                            <x-sortable-header column="kota" label="Kota" :currentSort="$sortColumn" :currentDirection="$sortDirection" />
                        </th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Kontak</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-bgray-600 dark:text-bgray-50">Status</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-bgray-600 dark:text-bgray-50">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paroki as $p)
                        <tr class="border-b border-bgray-200 last:border-0 dark:border-darkblack-400">
                            <td class="px-6 py-4">
                                <a href="{{ route('paroki.show', $p) }}" class="font-medium text-bgray-900 hover:text-accent-300 dark:text-white">
                                    {{ $p->nama }}
                                </a>
                                @if($p->alamat)
                                    <p class="text-sm text-bgray-500 dark:text-bgray-50">{{ Str::limit($p->alamat, 50) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-bgray-900 dark:text-white">
                                {{ $p->kevikepan->nama ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-bgray-900 dark:text-white">
                                {{ $p->kota ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($p->telepon || $p->email)
                                    @if($p->telepon)
                                        <span class="text-bgray-900 dark:text-white">{{ $p->telepon }}</span>
                                    @endif
                                    @if($p->email)
                                        <p class="text-sm text-bgray-500 dark:text-bgray-50">{{ $p->email }}</p>
                                    @endif
                                @else
                                    <span class="text-bgray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($p->is_active)
                                    <span class="inline-flex items-center rounded-full bg-accent-50 px-3 py-1 text-sm font-medium text-accent-400">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-bgray-100 px-3 py-1 text-sm font-medium text-bgray-600">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('paroki.show', $p) }}"
                                        class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500"
                                        title="Lihat Detail">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('paroki.edit', $p) }}"
                                        class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500"
                                        title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('paroki.destroy', $p) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus paroki {{ $p->nama }}?')">
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
                                <i class="fa fa-church mb-4 text-4xl text-bgray-300"></i>
                                <p>Belum ada data paroki</p>
                                <a href="{{ route('paroki.create') }}" class="mt-2 inline-block text-accent-300 hover:underline">
                                    Tambah paroki pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($paroki->hasPages())
            <div class="border-t border-bgray-200 px-6 py-4 dark:border-darkblack-400">
                {{ $paroki->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

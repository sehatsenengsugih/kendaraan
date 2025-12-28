<x-app-layout title="Kelola Panduan">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Kelola Panduan</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-400 mt-1">Kelola bagian-bagian panduan pengguna</p>
            </div>
            <a href="{{ route('manual.admin.create') }}" class="inline-flex items-center rounded-lg bg-accent-300 px-4 py-2 text-sm font-medium text-white hover:bg-accent-400 transition-colors">
                <i class="fa fa-plus mr-2"></i> Tambah Bagian
            </a>
        </div>
    </x-slot>

    <!-- Sections Table -->
    <div class="rounded-lg bg-white shadow-sm dark:bg-darkblack-600">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-bgray-50 dark:bg-darkblack-500">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-bgray-600 dark:text-bgray-300 w-16">Urutan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-bgray-600 dark:text-bgray-300">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-bgray-600 dark:text-bgray-300">Slug</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-bgray-600 dark:text-bgray-300">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-bgray-600 dark:text-bgray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-bgray-100 dark:divide-darkblack-400" id="sections-list">
                    @forelse($sections as $section)
                    <tr class="hover:bg-bgray-50 dark:hover:bg-darkblack-500" data-id="{{ $section->id }}">
                        <td class="px-6 py-4">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-bgray-100 text-sm font-medium text-bgray-600 dark:bg-darkblack-400 dark:text-bgray-300">
                                {{ $section->order }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($section->icon)
                                <div class="mr-3 text-accent-300">{!! $section->icon !!}</div>
                                @endif
                                <div>
                                    <p class="font-medium text-bgray-900 dark:text-white">{{ $section->title }}</p>
                                    @if($section->children->count() > 0)
                                    <p class="text-xs text-bgray-500 dark:text-bgray-400">{{ $section->children->count() }} sub-bagian</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <code class="text-sm text-bgray-600 dark:text-bgray-300 bg-bgray-100 dark:bg-darkblack-400 px-2 py-1 rounded">{{ $section->slug }}</code>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($section->is_active)
                            <span class="inline-flex items-center rounded-full bg-accent-50 px-3 py-1 text-xs font-medium text-accent-400">Aktif</span>
                            @else
                            <span class="inline-flex items-center rounded-full bg-bgray-100 px-3 py-1 text-xs font-medium text-bgray-600 dark:bg-darkblack-400 dark:text-bgray-400">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('manual.section', $section->slug) }}" target="_blank" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-bgray-500 hover:bg-bgray-100 dark:hover:bg-darkblack-400" title="Lihat">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('manual.admin.edit', $section) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-warning-300 hover:bg-warning-50 dark:hover:bg-darkblack-400" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('manual.admin.destroy', $section) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus bagian ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-error-300 hover:bg-error-50 dark:hover:bg-darkblack-400" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @if($section->children->count() > 0)
                        @foreach($section->children as $child)
                        <tr class="hover:bg-bgray-50 dark:hover:bg-darkblack-500 bg-bgray-50/50 dark:bg-darkblack-500/50" data-id="{{ $child->id }}">
                            <td class="px-6 py-4 pl-12">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded bg-bgray-100 text-xs font-medium text-bgray-500 dark:bg-darkblack-400 dark:text-bgray-400">
                                    {{ $child->order }}
                                </span>
                            </td>
                            <td class="px-6 py-4 pl-12">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-bgray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    <p class="text-bgray-700 dark:text-bgray-300">{{ $child->title }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-xs text-bgray-500 dark:text-bgray-400 bg-bgray-100 dark:bg-darkblack-400 px-2 py-1 rounded">{{ $child->slug }}</code>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($child->is_active)
                                <span class="inline-flex items-center rounded-full bg-accent-50 px-2 py-0.5 text-xs font-medium text-accent-400">Aktif</span>
                                @else
                                <span class="inline-flex items-center rounded-full bg-bgray-100 px-2 py-0.5 text-xs font-medium text-bgray-600 dark:bg-darkblack-400 dark:text-bgray-400">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('manual.admin.edit', $child) }}" class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-warning-300 hover:bg-warning-50 dark:hover:bg-darkblack-400" title="Edit">
                                        <i class="fa fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('manual.admin.destroy', $child) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus sub-bagian ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-error-300 hover:bg-error-50 dark:hover:bg-darkblack-400" title="Hapus">
                                            <i class="fa fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-bgray-300 dark:text-bgray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <p class="text-bgray-500 dark:text-bgray-400 mb-4">Belum ada bagian panduan</p>
                                <a href="{{ route('manual.admin.create') }}" class="inline-flex items-center rounded-lg bg-accent-300 px-4 py-2 text-sm font-medium text-white hover:bg-accent-400 transition-colors">
                                    <i class="fa fa-plus mr-2"></i> Buat Bagian Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 flex items-center gap-4">
        <a href="{{ route('manual.index') }}" class="inline-flex items-center text-sm text-bgray-600 hover:text-accent-300 dark:text-bgray-400 dark:hover:text-accent-300">
            <i class="fa fa-external-link-alt mr-2"></i> Lihat Halaman Panduan
        </a>
    </div>
</x-app-layout>

<x-app-layout title="Edit Bagian Panduan">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex items-center space-x-2 text-sm text-bgray-500 dark:text-bgray-400 mb-2">
                    <a href="{{ route('manual.admin.index') }}" class="hover:text-accent-300">Kelola Panduan</a>
                    <span>/</span>
                    <span class="text-bgray-900 dark:text-white">Edit: {{ $section->title }}</span>
                </nav>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Edit Bagian Panduan</h2>
            </div>
            <a href="{{ route('manual.section', $section->slug) }}" target="_blank" class="inline-flex items-center text-sm text-bgray-600 hover:text-accent-300 dark:text-bgray-400">
                <i class="fa fa-external-link-alt mr-2"></i> Lihat Halaman
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl">
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600">
            <form method="POST" action="{{ route('manual.admin.update', $section) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">
                            Judul <span class="text-error-300">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $section->title) }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="Contoh: Dashboard">
                        @error('title')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">
                            Slug
                        </label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $section->slug) }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="dashboard">
                        @error('slug')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Section -->
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">
                            Bagian Induk
                            <span class="text-bgray-400 text-xs">(kosongkan untuk bagian utama)</span>
                        </label>
                        <select name="parent_id" id="parent_id"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                            <option value="">-- Tidak ada (Bagian Utama) --</option>
                            @foreach($parentSections as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $section->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->title }}
                            </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div>
                        <label for="order" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">
                            Urutan
                        </label>
                        <input type="number" name="order" id="order" value="{{ old('order', $section->order) }}" min="0"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        @error('order')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Icon -->
                <div class="mt-6">
                    <label for="icon" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">
                        Ikon (SVG)
                        <span class="text-bgray-400 text-xs">(opsional)</span>
                    </label>
                    @if($section->icon)
                    <div class="mb-2 flex items-center gap-2">
                        <span class="text-bgray-500 text-sm">Preview:</span>
                        <div class="text-accent-300">{!! $section->icon !!}</div>
                    </div>
                    @endif
                    <textarea name="icon" id="icon" rows="3"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white font-mono text-sm"
                        placeholder='<svg class="w-5 h-5" ...>...</svg>'>{{ old('icon', $section->icon) }}</textarea>
                    @error('icon')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div class="mt-6">
                    <label for="content" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">
                        Konten <span class="text-error-300">*</span>
                    </label>
                    <p class="text-xs text-bgray-500 dark:text-bgray-400 mb-2">Gunakan HTML untuk format konten. Contoh: &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;img&gt;, dll.</p>
                    <textarea name="content" id="content" rows="20" required
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white font-mono text-sm">{{ old('content', $section->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="mt-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $section->is_active) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-bgray-300 text-accent-300 focus:ring-accent-300 dark:border-darkblack-400 dark:bg-darkblack-500">
                        <span class="ml-3 text-sm font-medium text-bgray-700 dark:text-bgray-300">Aktif</span>
                    </label>
                    <p class="text-xs text-bgray-500 dark:text-bgray-400 mt-1">Bagian yang tidak aktif tidak akan ditampilkan di halaman panduan</p>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex items-center justify-between pt-6 border-t border-bgray-100 dark:border-darkblack-400">
                    <form action="{{ route('manual.admin.destroy', $section) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus bagian ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center text-sm text-error-300 hover:text-error-400">
                            <i class="fa fa-trash mr-2"></i> Hapus Bagian
                        </button>
                    </form>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('manual.admin.index') }}" class="rounded-lg border border-bgray-300 px-6 py-3 text-sm font-medium text-bgray-700 hover:bg-bgray-50 dark:border-darkblack-400 dark:text-bgray-300 dark:hover:bg-darkblack-500 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="rounded-lg bg-accent-300 px-6 py-3 text-sm font-medium text-white hover:bg-accent-400 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

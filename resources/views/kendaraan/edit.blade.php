<x-app-layout>
    <x-slot name="title">Edit Kendaraan</x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('kendaraan.show', $kendaraan) }}"
                class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Edit Kendaraan</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">{{ $kendaraan->plat_nomor }} - {{ $kendaraan->display_name }}</p>
            </div>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('kendaraan.update', $kendaraan) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Info Kendaraan -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Informasi Kendaraan</h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        <!-- Jenis -->
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Jenis Kendaraan <span class="text-error-300">*</span>
                            </label>
                            <div class="flex gap-6">
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-bgray-200 px-4 py-3 transition-all hover:border-success-300 dark:border-darkblack-400">
                                    <input type="radio" name="jenis" value="mobil" {{ old('jenis', $kendaraan->jenis) === 'mobil' ? 'checked' : '' }}
                                        class="h-5 w-5 text-success-300 focus:ring-success-300">
                                    <span class="flex items-center gap-2 text-bgray-900 dark:text-white">
                                        <i class="fa fa-car text-blue-500"></i> Mobil
                                    </span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-bgray-200 px-4 py-3 transition-all hover:border-success-300 dark:border-darkblack-400">
                                    <input type="radio" name="jenis" value="motor" {{ old('jenis', $kendaraan->jenis) === 'motor' ? 'checked' : '' }}
                                        class="h-5 w-5 text-success-300 focus:ring-success-300">
                                    <span class="flex items-center gap-2 text-bgray-900 dark:text-white">
                                        <i class="fa fa-motorcycle text-orange-500"></i> Motor
                                    </span>
                                </label>
                            </div>
                            @error('jenis')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Plat Nomor -->
                        <div>
                            <label for="plat_nomor" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Plat Nomor <span class="text-error-300">*</span>
                            </label>
                            <input type="text" name="plat_nomor" id="plat_nomor" value="{{ old('plat_nomor', $kendaraan->plat_nomor) }}" required
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 font-mono uppercase text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('plat_nomor') border-error-300 @enderror">
                            @error('plat_nomor')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor BPKB -->
                        <div>
                            <label for="nomor_bpkb" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Nomor BPKB <span class="text-error-300">*</span>
                            </label>
                            <input type="text" name="nomor_bpkb" id="nomor_bpkb" value="{{ old('nomor_bpkb', $kendaraan->nomor_bpkb) }}" required
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('nomor_bpkb') border-error-300 @enderror">
                            @error('nomor_bpkb')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Merk -->
                        <div>
                            <label for="merk_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Merk <span class="text-error-300">*</span>
                            </label>
                            <select name="merk_id" id="merk_id" required
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('merk_id') border-error-300 @enderror">
                                <option value="">Pilih Merk</option>
                                @foreach($merk as $m)
                                    <option value="{{ $m->id }}" {{ old('merk_id', $kendaraan->merk_id) == $m->id ? 'selected' : '' }}>
                                        {{ $m->nama }} ({{ ucfirst($m->jenis) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('merk_id')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Model -->
                        <div>
                            <label for="nama_model" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Model/Tipe <span class="text-error-300">*</span>
                            </label>
                            <input type="text" name="nama_model" id="nama_model" value="{{ old('nama_model', $kendaraan->nama_model) }}" required
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('nama_model') border-error-300 @enderror">
                            @error('nama_model')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tahun -->
                        <div>
                            <label for="tahun_pembuatan" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Tahun Pembuatan <span class="text-error-300">*</span>
                            </label>
                            <input type="number" name="tahun_pembuatan" id="tahun_pembuatan" value="{{ old('tahun_pembuatan', $kendaraan->tahun_pembuatan) }}" required
                                min="1900" max="{{ date('Y') + 1 }}"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tahun_pembuatan') border-error-300 @enderror">
                            @error('tahun_pembuatan')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Warna -->
                        <div>
                            <label for="warna" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Warna <span class="text-error-300">*</span>
                            </label>
                            <input type="text" name="warna" id="warna" value="{{ old('warna', $kendaraan->warna) }}" required
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('warna') border-error-300 @enderror">
                            @error('warna')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Lokasi & Pemegang -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Lokasi & Pemegang</h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        <!-- Garasi -->
                        <div>
                            <label for="garasi_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Garasi <span class="text-error-300">*</span>
                            </label>
                            <select name="garasi_id" id="garasi_id" required
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('garasi_id') border-error-300 @enderror">
                                <option value="">Pilih Garasi</option>
                                @foreach($garasi as $g)
                                    <option value="{{ $g->id }}" {{ old('garasi_id', $kendaraan->garasi_id) == $g->id ? 'selected' : '' }}>
                                        {{ $g->nama }} ({{ $g->kevikepan->nama ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('garasi_id')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pemegang -->
                        <div>
                            <label for="pemegang_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Pemegang
                            </label>
                            <select name="pemegang_id" id="pemegang_id"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('pemegang_id') border-error-300 @enderror">
                                <option value="">- Tidak Ada Pemegang -</option>
                                @foreach($pemegang as $p)
                                    <option value="{{ $p->id }}" {{ old('pemegang_id', $kendaraan->pemegang_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->organization_name ?? ucfirst($p->user_type) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('pemegang_id')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Status <span class="text-error-300">*</span>
                            </label>
                            <select name="status" id="status" required
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('status') border-error-300 @enderror">
                                <option value="aktif" {{ old('status', $kendaraan->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $kendaraan->status) === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                                <option value="dihibahkan" {{ old('status', $kendaraan->status) === 'dihibahkan' ? 'selected' : '' }}>Dihibahkan</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Perolehan -->
                        <div>
                            <label for="tanggal_perolehan" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Tanggal Perolehan <span class="text-error-300">*</span>
                            </label>
                            <input type="date" name="tanggal_perolehan" id="tanggal_perolehan"
                                value="{{ old('tanggal_perolehan', $kendaraan->tanggal_perolehan?->format('Y-m-d')) }}" required
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tanggal_perolehan') border-error-300 @enderror">
                            @error('tanggal_perolehan')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Hibah -->
                        <div>
                            <label for="tanggal_hibah" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Tanggal Hibah
                            </label>
                            <input type="date" name="tanggal_hibah" id="tanggal_hibah"
                                value="{{ old('tanggal_hibah', $kendaraan->tanggal_hibah?->format('Y-m-d')) }}"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tanggal_hibah') border-error-300 @enderror">
                            @error('tanggal_hibah')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="md:col-span-2">
                            <label for="catatan" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Catatan
                            </label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('catatan') border-error-300 @enderror">{{ old('catatan', $kendaraan->catatan) }}</textarea>
                            @error('catatan')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Images -->
            <div class="space-y-6">
                <!-- Avatar -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Foto Utama</h3>

                    <div class="mb-4">
                        <div id="avatar-preview" class="mb-4 flex h-48 items-center justify-center rounded-lg border-2 border-dashed border-bgray-200 bg-bgray-50 dark:border-darkblack-400 dark:bg-darkblack-500 overflow-hidden">
                            @if($kendaraan->avatar_path)
                                <img src="{{ asset('storage/' . $kendaraan->avatar_path) }}" class="h-full w-full object-cover">
                            @else
                                <div class="text-center text-bgray-400">
                                    <i class="fa fa-image text-4xl"></i>
                                    <p class="mt-2 text-sm">Tidak ada foto</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp"
                            class="w-full text-sm text-bgray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-success-300 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-success-400">
                        <p class="mt-1 text-xs text-bgray-500">Kosongkan jika tidak ingin mengubah foto.</p>
                        @error('avatar')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Existing Gallery -->
                @if($kendaraan->gambar->count() > 0)
                    <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                        <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Foto Saat Ini</h3>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($kendaraan->gambar as $gambar)
                                <div class="relative aspect-square overflow-hidden rounded-lg bg-bgray-100">
                                    <img src="{{ $gambar->url }}" class="h-full w-full object-cover">
                                    <label class="absolute inset-0 flex cursor-pointer items-center justify-center bg-black/50 opacity-0 transition-opacity hover:opacity-100">
                                        <input type="checkbox" name="delete_gambar[]" value="{{ $gambar->id }}"
                                            class="h-5 w-5 rounded border-white text-error-300">
                                        <span class="ml-2 text-sm text-white">Hapus</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-2 text-xs text-bgray-500">Centang foto yang ingin dihapus.</p>
                    </div>
                @endif

                <!-- New Gallery -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Tambah Foto</h3>

                    <div class="mb-4">
                        <input type="file" name="gambar[]" id="gambar" accept="image/jpeg,image/png,image/webp" multiple
                            class="w-full text-sm text-bgray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-success-300 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-success-400">
                        <p class="mt-1 text-xs text-bgray-500">Pilih beberapa gambar sekaligus. Maks 2MB per file.</p>
                        @error('gambar.*')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="gambar-preview" class="grid grid-cols-3 gap-2"></div>
                </div>

                <!-- Submit -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <button type="submit"
                        class="w-full rounded-lg bg-success-300 px-6 py-3 font-semibold text-white transition-all hover:bg-success-400">
                        <i class="fa fa-save mr-2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('kendaraan.show', $kendaraan) }}"
                        class="mt-3 block w-full rounded-lg border border-bgray-300 px-6 py-3 text-center font-semibold text-bgray-600 transition-all hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        // Avatar preview
        document.getElementById('avatar').addEventListener('change', function(e) {
            const preview = document.getElementById('avatar-preview');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" class="h-full w-full object-cover">';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Gallery preview
        document.getElementById('gambar').addEventListener('change', function(e) {
            const preview = document.getElementById('gambar-preview');
            preview.innerHTML = '';
            if (this.files) {
                Array.from(this.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'aspect-square overflow-hidden rounded-lg bg-bgray-100';
                        div.innerHTML = '<img src="' + e.target.result + '" class="h-full w-full object-cover">';
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>

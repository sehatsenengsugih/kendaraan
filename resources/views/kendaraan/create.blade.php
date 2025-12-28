<x-app-layout>
    <x-slot name="title">Tambah Kendaraan</x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('kendaraan.index') }}"
                class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Tambah Kendaraan</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">Registrasi kendaraan baru</p>
            </div>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('kendaraan.store') }}" enctype="multipart/form-data" id="kendaraanForm">
        @csrf

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
                                    <input type="radio" name="jenis" value="mobil" {{ old('jenis', 'mobil') === 'mobil' ? 'checked' : '' }}
                                        class="h-5 w-5 text-success-300 focus:ring-success-300">
                                    <span class="flex items-center gap-2 text-bgray-900 dark:text-white">
                                        <i class="fa fa-car text-blue-500"></i> Mobil
                                    </span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-bgray-200 px-4 py-3 transition-all hover:border-success-300 dark:border-darkblack-400">
                                    <input type="radio" name="jenis" value="motor" {{ old('jenis') === 'motor' ? 'checked' : '' }}
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
                            <input type="text" name="plat_nomor" id="plat_nomor" value="{{ old('plat_nomor') }}" required
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 font-mono uppercase text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('plat_nomor') border-error-300 @enderror"
                                placeholder="H 1234 AB">
                            @error('plat_nomor')
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
                                    <option value="{{ $m->id }}" data-jenis="{{ $m->jenis }}"
                                        {{ old('merk_id', $selectedMerkId) == $m->id ? 'selected' : '' }}>
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
                            <input type="text" name="nama_model" id="nama_model" value="{{ old('nama_model') }}" required
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('nama_model') border-error-300 @enderror"
                                placeholder="Contoh: Avanza, Beat, dll">
                            @error('nama_model')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tahun -->
                        <div>
                            <label for="tahun_pembuatan" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Tahun Pembuatan <span class="text-error-300">*</span>
                            </label>
                            <input type="number" name="tahun_pembuatan" id="tahun_pembuatan" value="{{ old('tahun_pembuatan') }}" required
                                min="1900" max="{{ date('Y') + 1 }}"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tahun_pembuatan') border-error-300 @enderror"
                                placeholder="{{ date('Y') }}">
                            @error('tahun_pembuatan')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Warna -->
                        <div>
                            <label for="warna" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Warna <span class="text-error-300">*</span>
                            </label>
                            <input type="text" name="warna" id="warna" value="{{ old('warna') }}" required
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('warna') border-error-300 @enderror"
                                placeholder="Contoh: Putih, Hitam Metalik">
                            @error('warna')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dokumen & Identitas -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Dokumen & Identitas</h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        <!-- Status BPKB -->
                        <div>
                            <label for="status_bpkb_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Status BPKB
                            </label>
                            <select name="status_bpkb_id" id="status_bpkb_id"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('status_bpkb_id') border-error-300 @enderror">
                                <option value="">-- Pilih Status BPKB --</option>
                                @foreach($statusBpkb as $status)
                                    <option value="{{ $status->id }}" {{ old('status_bpkb_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_bpkb_id')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor BPKB (conditional - show when status is selected) -->
                        <div id="bpkb-field" class="{{ old('status_bpkb_id') ? '' : 'hidden' }}">
                            <label for="nomor_bpkb" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Nomor BPKB
                            </label>
                            <input type="text" name="nomor_bpkb" id="nomor_bpkb" value="{{ old('nomor_bpkb') }}"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('nomor_bpkb') border-error-300 @enderror"
                                placeholder="Masukkan nomor BPKB">
                            @error('nomor_bpkb')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Rangka -->
                        <div>
                            <label for="nomor_rangka" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Nomor Rangka
                            </label>
                            <input type="text" name="nomor_rangka" id="nomor_rangka" value="{{ old('nomor_rangka') }}"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 font-mono uppercase text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('nomor_rangka') border-error-300 @enderror"
                                placeholder="Masukkan nomor rangka">
                            @error('nomor_rangka')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Mesin -->
                        <div>
                            <label for="nomor_mesin" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Nomor Mesin
                            </label>
                            <input type="text" name="nomor_mesin" id="nomor_mesin" value="{{ old('nomor_mesin') }}"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 font-mono uppercase text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('nomor_mesin') border-error-300 @enderror"
                                placeholder="Masukkan nomor mesin">
                            @error('nomor_mesin')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Kepemilikan -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Status Kepemilikan</h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        <!-- Status Kepemilikan Radio -->
                        <div class="md:col-span-2">
                            <div class="flex gap-6">
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-bgray-200 px-4 py-3 transition-all hover:border-success-300 dark:border-darkblack-400">
                                    <input type="radio" name="status_kepemilikan" value="milik_kas" {{ old('status_kepemilikan', 'milik_kas') === 'milik_kas' ? 'checked' : '' }}
                                        class="h-5 w-5 text-success-300 focus:ring-success-300">
                                    <span class="text-bgray-900 dark:text-white">Milik KAS</span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-bgray-200 px-4 py-3 transition-all hover:border-success-300 dark:border-darkblack-400">
                                    <input type="radio" name="status_kepemilikan" value="milik_lembaga_lain" {{ old('status_kepemilikan') === 'milik_lembaga_lain' ? 'checked' : '' }}
                                        class="h-5 w-5 text-success-300 focus:ring-success-300">
                                    <span class="text-bgray-900 dark:text-white">Milik Lembaga Lain</span>
                                </label>
                            </div>
                            @error('status_kepemilikan')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Lembaga (conditional) -->
                        <div id="lembaga-field" class="md:col-span-2 {{ old('status_kepemilikan') === 'milik_lembaga_lain' ? '' : 'hidden' }}">
                            <label for="pemilik_lembaga_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Lembaga Pemilik <span class="text-error-300">*</span>
                            </label>
                            <select name="pemilik_lembaga_id" id="pemilik_lembaga_id"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('pemilik_lembaga_id') border-error-300 @enderror">
                                <option value="">Pilih Lembaga</option>
                                @foreach($lembaga as $l)
                                    <option value="{{ $l->id }}" {{ old('pemilik_lembaga_id') == $l->id ? 'selected' : '' }}>
                                        {{ $l->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pemilik_lembaga_id')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                            <input type="hidden" name="nama_pemilik_lembaga" id="nama_pemilik_lembaga" value="{{ old('nama_pemilik_lembaga') }}">
                        </div>
                    </div>
                </div>

                <!-- Lokasi & Pemegang -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Lokasi & Status</h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        <!-- Garasi -->
                        <div>
                            <label for="garasi_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Garasi
                            </label>
                            <select name="garasi_id" id="garasi_id"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('garasi_id') border-error-300 @enderror">
                                <option value="">Pilih Garasi</option>
                                @foreach($garasi as $g)
                                    <option value="{{ $g->id }}" {{ old('garasi_id', $selectedGarasiId) == $g->id ? 'selected' : '' }}>
                                        {{ $g->nama }} ({{ $g->kevikepan->nama ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('garasi_id')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pengguna Saat Ini -->
                        <div>
                            <label for="pemegang_nama" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Pengguna Saat Ini
                            </label>
                            <input type="text" name="pemegang_nama" id="pemegang_nama"
                                value="{{ old('pemegang_nama') }}"
                                placeholder="Nama pengguna kendaraan saat ini..."
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('pemegang_nama') border-error-300 @enderror">
                            @error('pemegang_nama')
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
                                <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                                <option value="dihibahkan" {{ old('status') === 'dihibahkan' ? 'selected' : '' }}>Dihibahkan</option>
                                <option value="dijual" {{ old('status') === 'dijual' ? 'selected' : '' }}>Dijual</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Perolehan -->
                        <div>
                            <label for="tanggal_perolehan" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Tanggal Perolehan
                            </label>
                            <input type="date" name="tanggal_perolehan" id="tanggal_perolehan" value="{{ old('tanggal_perolehan') }}"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tanggal_perolehan') border-error-300 @enderror">
                            @error('tanggal_perolehan')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Info Pembelian -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Info Pembelian</h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        <!-- Tanggal Beli -->
                        <div>
                            <label for="tanggal_beli" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Tanggal Beli
                            </label>
                            <input type="date" name="tanggal_beli" id="tanggal_beli" value="{{ old('tanggal_beli') }}"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tanggal_beli') border-error-300 @enderror">
                            @error('tanggal_beli')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Harga Beli -->
                        <div>
                            <label for="harga_beli" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Harga Beli
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-bgray-500">Rp</span>
                                <input type="number" name="harga_beli" id="harga_beli" value="{{ old('harga_beli') }}" min="0" step="1"
                                    class="w-full rounded-lg border border-bgray-200 pl-12 pr-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('harga_beli') border-error-300 @enderror"
                                    placeholder="0">
                            </div>
                            @error('harga_beli')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Info Hibah (conditional) -->
                <div id="hibah-section" class="rounded-lg bg-white p-6 dark:bg-darkblack-600 {{ old('status') === 'dihibahkan' ? '' : 'hidden' }}">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Info Hibah</h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        <!-- Tanggal Hibah -->
                        <div>
                            <label for="tanggal_hibah" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Tanggal Hibah
                            </label>
                            <input type="date" name="tanggal_hibah" id="tanggal_hibah" value="{{ old('tanggal_hibah') }}"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tanggal_hibah') border-error-300 @enderror">
                            @error('tanggal_hibah')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Penerima Hibah -->
                        <div>
                            <label for="nama_penerima_hibah" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Nama Penerima Hibah <span class="text-error-300">*</span>
                            </label>
                            <input type="text" name="nama_penerima_hibah" id="nama_penerima_hibah" value="{{ old('nama_penerima_hibah') }}"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('nama_penerima_hibah') border-error-300 @enderror"
                                placeholder="Nama penerima hibah">
                            @error('nama_penerima_hibah')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Info Penjualan (conditional) -->
                <div id="jual-section" class="rounded-lg bg-white p-6 dark:bg-darkblack-600 {{ old('status') === 'dijual' ? '' : 'hidden' }}">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Info Penjualan</h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        <!-- Tanggal Jual -->
                        <div>
                            <label for="tanggal_jual" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Tanggal Jual
                            </label>
                            <input type="date" name="tanggal_jual" id="tanggal_jual" value="{{ old('tanggal_jual') }}"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tanggal_jual') border-error-300 @enderror">
                            @error('tanggal_jual')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Harga Jual -->
                        <div>
                            <label for="harga_jual" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Harga Jual
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-bgray-500">Rp</span>
                                <input type="number" name="harga_jual" id="harga_jual" value="{{ old('harga_jual') }}" min="0" step="1"
                                    class="w-full rounded-lg border border-bgray-200 pl-12 pr-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('harga_jual') border-error-300 @enderror"
                                    placeholder="0">
                            </div>
                            @error('harga_jual')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Pembeli -->
                        <div class="md:col-span-2">
                            <label for="nama_pembeli" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Nama Pembeli <span class="text-error-300">*</span>
                            </label>
                            <input type="text" name="nama_pembeli" id="nama_pembeli" value="{{ old('nama_pembeli') }}"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('nama_pembeli') border-error-300 @enderror"
                                placeholder="Nama pembeli kendaraan">
                            @error('nama_pembeli')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status Pinjam -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Status Pinjam</h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        <!-- Is Dipinjam Checkbox -->
                        <div class="md:col-span-2">
                            <label class="flex cursor-pointer items-center gap-3">
                                <input type="checkbox" name="is_dipinjam" id="is_dipinjam" value="1" {{ old('is_dipinjam') ? 'checked' : '' }}
                                    class="h-5 w-5 rounded border-bgray-300 text-success-300 focus:ring-success-300">
                                <span class="text-sm font-medium text-bgray-900 dark:text-white">Sedang Dipinjam</span>
                            </label>
                        </div>

                        <!-- Pinjam Fields (conditional) -->
                        <div id="pinjam-fields" class="md:col-span-2 {{ old('is_dipinjam') ? '' : 'hidden' }}">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="dipinjam_paroki_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                        Dipinjam Oleh (Paroki)
                                    </label>
                                    <select name="dipinjam_paroki_id" id="dipinjam_paroki_id"
                                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('dipinjam_paroki_id') border-error-300 @enderror">
                                        <option value="">Pilih Paroki</option>
                                        @foreach($paroki as $p)
                                            <option value="{{ $p->id }}" {{ old('dipinjam_paroki_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama }} ({{ $p->kevikepan->nama ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dipinjam_paroki_id')
                                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                                    @enderror
                                    <input type="hidden" name="dipinjam_oleh" id="dipinjam_oleh" value="{{ old('dipinjam_oleh') }}">
                                </div>

                                <div>
                                    <label for="tanggal_pinjam" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                        Tanggal Pinjam
                                    </label>
                                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}"
                                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tanggal_pinjam') border-error-300 @enderror">
                                    @error('tanggal_pinjam')
                                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Tarikan -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Info Tarikan</h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        <!-- Is Tarikan Checkbox -->
                        <div class="md:col-span-2">
                            <label class="flex cursor-pointer items-center gap-3">
                                <input type="checkbox" name="is_tarikan" id="is_tarikan" value="1" {{ old('is_tarikan') ? 'checked' : '' }}
                                    class="h-5 w-5 rounded border-bgray-300 text-success-300 focus:ring-success-300">
                                <span class="text-sm font-medium text-bgray-900 dark:text-white">Kendaraan Tarikan</span>
                            </label>
                            <p class="mt-1 text-xs text-bgray-500">Centang jika kendaraan ini merupakan tarikan dari tempat lain</p>
                        </div>

                        <!-- Tarikan Fields (conditional) -->
                        <div id="tarikan-fields" class="md:col-span-2 {{ old('is_tarikan') ? '' : 'hidden' }}">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="tarikan_paroki_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                        Ditarik Dari Paroki
                                    </label>
                                    <select name="tarikan_paroki_id" id="tarikan_paroki_id"
                                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tarikan_paroki_id') border-error-300 @enderror">
                                        <option value="">- Tidak Ada -</option>
                                        @foreach($paroki as $p)
                                            <option value="{{ $p->id }}" {{ old('tarikan_paroki_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama }} ({{ $p->kevikepan->nama ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tarikan_paroki_id')
                                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tarikan_lembaga_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                        Ditarik Dari Lembaga
                                    </label>
                                    <select name="tarikan_lembaga_id" id="tarikan_lembaga_id"
                                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tarikan_lembaga_id') border-error-300 @enderror">
                                        <option value="">- Tidak Ada -</option>
                                        @foreach($lembaga as $l)
                                            <option value="{{ $l->id }}" {{ old('tarikan_lembaga_id') == $l->id ? 'selected' : '' }}>
                                                {{ $l->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tarikan_lembaga_id')
                                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                                    @enderror
                                    <input type="hidden" name="tarikan_dari" id="tarikan_dari" value="{{ old('tarikan_dari') }}">
                                </div>

                                <div>
                                    <label for="tarikan_pemakai" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                        Pengguna Sebelumnya
                                    </label>
                                    <input type="text" name="tarikan_pemakai" id="tarikan_pemakai" value="{{ old('tarikan_pemakai') }}"
                                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tarikan_pemakai') border-error-300 @enderror"
                                        placeholder="Nama pengguna sebelumnya">
                                    @error('tarikan_pemakai')
                                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="tarikan_kondisi" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                        Kondisi Saat Ditarik
                                    </label>
                                    <textarea name="tarikan_kondisi" id="tarikan_kondisi" rows="3"
                                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tarikan_kondisi') border-error-300 @enderror"
                                        placeholder="Deskripsi kondisi kendaraan saat ditarik">{{ old('tarikan_kondisi') }}</textarea>
                                    @error('tarikan_kondisi')
                                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pengguna -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-bgray-900 dark:text-white">Riwayat Pengguna</h3>
                        <button type="button" id="add-riwayat" class="rounded-lg bg-success-300 px-4 py-2 text-sm font-medium text-white hover:bg-success-400">
                            <i class="fa fa-plus mr-1"></i> Tambah Riwayat
                        </button>
                    </div>

                    <div id="riwayat-container" class="space-y-4">
                        <!-- Dynamic rows will be added here -->
                        @if(old('riwayat_pemakai'))
                            @foreach(old('riwayat_pemakai') as $index => $riwayat)
                                <div class="riwayat-row rounded-lg border border-bgray-200 p-4 dark:border-darkblack-400">
                                    <div class="mb-4 flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-bgray-700 dark:text-bgray-200">Riwayat #{{ $index + 1 }}</h4>
                                        <button type="button" class="remove-riwayat text-error-300 hover:text-error-400">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Jenis Pengguna</label>
                                            <select name="riwayat_pemakai[{{ $index }}][jenis_pemakai]" class="jenis-pemakai-select w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                                <option value="paroki" {{ ($riwayat['jenis_pemakai'] ?? '') === 'paroki' ? 'selected' : '' }}>Paroki</option>
                                                <option value="lembaga" {{ ($riwayat['jenis_pemakai'] ?? '') === 'lembaga' ? 'selected' : '' }}>Lembaga</option>
                                                <option value="pribadi" {{ ($riwayat['jenis_pemakai'] ?? '') === 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                                            </select>
                                        </div>
                                        <div class="riwayat-paroki-field {{ ($riwayat['jenis_pemakai'] ?? '') === 'paroki' ? '' : 'hidden' }}">
                                            <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Paroki</label>
                                            <select name="riwayat_pemakai[{{ $index }}][paroki_id]" class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                                <option value="">Pilih Paroki</option>
                                                @foreach($paroki as $p)
                                                    <option value="{{ $p->id }}" {{ ($riwayat['paroki_id'] ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="riwayat-lembaga-field {{ ($riwayat['jenis_pemakai'] ?? '') === 'lembaga' ? '' : 'hidden' }}">
                                            <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Lembaga</label>
                                            <select name="riwayat_pemakai[{{ $index }}][lembaga_id]" class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                                <option value="">Pilih Lembaga</option>
                                                @foreach($lembaga as $l)
                                                    <option value="{{ $l->id }}" {{ ($riwayat['lembaga_id'] ?? '') == $l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="riwayat-pribadi-field {{ ($riwayat['jenis_pemakai'] ?? '') === 'pribadi' ? '' : 'hidden' }}">
                                            <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Nama Pengguna</label>
                                            <input type="text" name="riwayat_pemakai[{{ $index }}][nama_pemakai]" value="{{ $riwayat['nama_pemakai'] ?? '' }}"
                                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                                                placeholder="Nama pribadi">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Tanggal Mulai</label>
                                            <input type="date" name="riwayat_pemakai[{{ $index }}][tanggal_mulai]" value="{{ $riwayat['tanggal_mulai'] ?? '' }}"
                                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Tanggal Selesai</label>
                                            <input type="date" name="riwayat_pemakai[{{ $index }}][tanggal_selesai]" value="{{ $riwayat['tanggal_selesai'] ?? '' }}"
                                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Catatan</label>
                                            <textarea name="riwayat_pemakai[{{ $index }}][catatan]" rows="2"
                                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                                                placeholder="Catatan tentang penggunaan">{{ $riwayat['catatan'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <p id="riwayat-empty" class="text-center text-sm text-bgray-500 py-8 {{ old('riwayat_pemakai') ? 'hidden' : '' }}">
                        Belum ada riwayat pengguna. Klik "Tambah Riwayat" untuk menambahkan.
                    </p>
                </div>

                <!-- Catatan -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Catatan</h3>

                    <div>
                        <textarea name="catatan" id="catatan" rows="3"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('catatan') border-error-300 @enderror"
                            placeholder="Catatan tambahan tentang kendaraan">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sidebar - Images -->
            <div class="space-y-6">
                <!-- Avatar -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Foto Utama</h3>

                    <div class="mb-4">
                        <div id="avatar-preview" class="mb-4 flex h-48 items-center justify-center rounded-lg border-2 border-dashed border-bgray-200 bg-bgray-50 dark:border-darkblack-400 dark:bg-darkblack-500">
                            <div class="text-center text-bgray-400">
                                <i class="fa fa-image text-4xl"></i>
                                <p class="mt-2 text-sm">Preview foto</p>
                            </div>
                        </div>
                        <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp"
                            class="w-full text-sm text-bgray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-success-300 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-success-400">
                        <p class="mt-1 text-xs text-bgray-500">JPG, PNG, atau WebP. Maks 2MB.</p>
                        @error('avatar')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Gallery -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Foto Tambahan</h3>

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
                        <i class="fa fa-save mr-2"></i> Simpan Kendaraan
                    </button>
                    <a href="{{ route('kendaraan.index') }}"
                        class="mt-3 block w-full rounded-lg border border-bgray-300 px-6 py-3 text-center font-semibold text-bgray-600 transition-all hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        // Toggle BPKB field based on status selection
        document.getElementById('status_bpkb_id').addEventListener('change', function() {
            const hasValue = this.value !== '';
            document.getElementById('bpkb-field').classList.toggle('hidden', !hasValue);
            if (!hasValue) {
                document.getElementById('nomor_bpkb').value = '';
            }
        });

        // Toggle Lembaga field
        document.querySelectorAll('input[name="status_kepemilikan"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('lembaga-field').classList.toggle('hidden', this.value !== 'milik_lembaga_lain');
            });
        });

        // Toggle status-dependent sections
        document.getElementById('status').addEventListener('change', function() {
            document.getElementById('hibah-section').classList.toggle('hidden', this.value !== 'dihibahkan');
            document.getElementById('jual-section').classList.toggle('hidden', this.value !== 'dijual');
        });

        // Toggle Pinjam fields
        document.getElementById('is_dipinjam').addEventListener('change', function() {
            document.getElementById('pinjam-fields').classList.toggle('hidden', !this.checked);
        });

        // Toggle Tarikan fields
        document.getElementById('is_tarikan').addEventListener('change', function() {
            document.getElementById('tarikan-fields').classList.toggle('hidden', !this.checked);
        });

        // Paroki options for template
        const parokiOptions = `@foreach($paroki as $p)<option value="{{ $p->id }}">{{ $p->nama }}</option>@endforeach`;
        const lembagaOptions = `@foreach($lembaga as $l)<option value="{{ $l->id }}">{{ $l->nama }}</option>@endforeach`;

        // Toggle riwayat pengguna fields based on jenis
        function setupJenisPemakaiHandler(row) {
            const select = row.querySelector('.jenis-pemakai-select');
            const parokiField = row.querySelector('.riwayat-paroki-field');
            const lembagaField = row.querySelector('.riwayat-lembaga-field');
            const pribadiField = row.querySelector('.riwayat-pribadi-field');

            select.addEventListener('change', function() {
                parokiField.classList.toggle('hidden', this.value !== 'paroki');
                lembagaField.classList.toggle('hidden', this.value !== 'lembaga');
                pribadiField.classList.toggle('hidden', this.value !== 'pribadi');
            });
        }

        // Attach handler to existing rows
        document.querySelectorAll('.riwayat-row').forEach(setupJenisPemakaiHandler);

        // Riwayat Pemakai dynamic form
        let riwayatIndex = {{ old('riwayat_pemakai') ? count(old('riwayat_pemakai')) : 0 }};

        document.getElementById('add-riwayat').addEventListener('click', function() {
            const container = document.getElementById('riwayat-container');
            const emptyMsg = document.getElementById('riwayat-empty');
            emptyMsg.classList.add('hidden');

            const template = `
                <div class="riwayat-row rounded-lg border border-bgray-200 p-4 dark:border-darkblack-400">
                    <div class="mb-4 flex items-center justify-between">
                        <h4 class="text-sm font-medium text-bgray-700 dark:text-bgray-200">Riwayat Baru</h4>
                        <button type="button" class="remove-riwayat text-error-300 hover:text-error-400">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Jenis Pengguna</label>
                            <select name="riwayat_pemakai[${riwayatIndex}][jenis_pemakai]" class="jenis-pemakai-select w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                <option value="paroki">Paroki</option>
                                <option value="lembaga">Lembaga</option>
                                <option value="pribadi">Pribadi</option>
                            </select>
                        </div>
                        <div class="riwayat-paroki-field">
                            <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Paroki</label>
                            <select name="riwayat_pemakai[${riwayatIndex}][paroki_id]" class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                <option value="">Pilih Paroki</option>
                                ${parokiOptions}
                            </select>
                        </div>
                        <div class="riwayat-lembaga-field hidden">
                            <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Lembaga</label>
                            <select name="riwayat_pemakai[${riwayatIndex}][lembaga_id]" class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                <option value="">Pilih Lembaga</option>
                                ${lembagaOptions}
                            </select>
                        </div>
                        <div class="riwayat-pribadi-field hidden">
                            <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Nama Pengguna</label>
                            <input type="text" name="riwayat_pemakai[${riwayatIndex}][nama_pemakai]"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                                placeholder="Nama pribadi">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Tanggal Mulai</label>
                            <input type="date" name="riwayat_pemakai[${riwayatIndex}][tanggal_mulai]"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Tanggal Selesai</label>
                            <input type="date" name="riwayat_pemakai[${riwayatIndex}][tanggal_selesai]"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Catatan</label>
                            <textarea name="riwayat_pemakai[${riwayatIndex}][catatan]" rows="2"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                                placeholder="Catatan tentang penggunaan"></textarea>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', template);
            riwayatIndex++;

            const newRow = container.lastElementChild;

            // Attach remove handler
            newRow.querySelector('.remove-riwayat').addEventListener('click', function() {
                this.closest('.riwayat-row').remove();
                if (container.querySelectorAll('.riwayat-row').length === 0) {
                    emptyMsg.classList.remove('hidden');
                }
            });

            // Attach jenis pemakai handler
            setupJenisPemakaiHandler(newRow);
        });

        // Attach remove handler to existing rows
        document.querySelectorAll('.remove-riwayat').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.riwayat-row').remove();
                const container = document.getElementById('riwayat-container');
                const emptyMsg = document.getElementById('riwayat-empty');
                if (container.querySelectorAll('.riwayat-row').length === 0) {
                    emptyMsg.classList.remove('hidden');
                }
            });
        });

        // Avatar preview
        document.getElementById('avatar').addEventListener('change', function(e) {
            const preview = document.getElementById('avatar-preview');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" class="h-full w-full object-cover rounded-lg">';
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

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

    <form method="POST" action="{{ route('kendaraan.update', $kendaraan) }}" enctype="multipart/form-data" id="kendaraanForm">
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
                                    <option value="{{ $status->id }}" {{ old('status_bpkb_id', $kendaraan->status_bpkb_id) == $status->id ? 'selected' : '' }}>
                                        {{ $status->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_bpkb_id')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor BPKB (conditional - show when status is selected) -->
                        <div id="bpkb-field" class="{{ old('status_bpkb_id', $kendaraan->status_bpkb_id) ? '' : 'hidden' }}">
                            <label for="nomor_bpkb" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Nomor BPKB
                            </label>
                            <input type="text" name="nomor_bpkb" id="nomor_bpkb" value="{{ old('nomor_bpkb', $kendaraan->nomor_bpkb) }}"
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
                            <input type="text" name="nomor_rangka" id="nomor_rangka" value="{{ old('nomor_rangka', $kendaraan->nomor_rangka) }}"
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
                            <input type="text" name="nomor_mesin" id="nomor_mesin" value="{{ old('nomor_mesin', $kendaraan->nomor_mesin) }}"
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
                                    <input type="radio" name="status_kepemilikan" value="milik_kas" {{ old('status_kepemilikan', $kendaraan->status_kepemilikan) === 'milik_kas' ? 'checked' : '' }}
                                        class="h-5 w-5 text-success-300 focus:ring-success-300">
                                    <span class="text-bgray-900 dark:text-white">Milik KAS</span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-bgray-200 px-4 py-3 transition-all hover:border-success-300 dark:border-darkblack-400">
                                    <input type="radio" name="status_kepemilikan" value="milik_lembaga_lain" {{ old('status_kepemilikan', $kendaraan->status_kepemilikan) === 'milik_lembaga_lain' ? 'checked' : '' }}
                                        class="h-5 w-5 text-success-300 focus:ring-success-300">
                                    <span class="text-bgray-900 dark:text-white">Milik Lembaga Lain</span>
                                </label>
                            </div>
                            @error('status_kepemilikan')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Lembaga (conditional) -->
                        <div id="lembaga-field" class="md:col-span-2 {{ old('status_kepemilikan', $kendaraan->status_kepemilikan) === 'milik_lembaga_lain' ? '' : 'hidden' }}">
                            <label for="pemilik_lembaga_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Lembaga Pemilik <span class="text-error-300">*</span>
                            </label>
                            <select name="pemilik_lembaga_id" id="pemilik_lembaga_id"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('pemilik_lembaga_id') border-error-300 @enderror">
                                <option value="">Pilih Lembaga</option>
                                @foreach($lembaga as $l)
                                    <option value="{{ $l->id }}" {{ old('pemilik_lembaga_id', $kendaraan->pemilik_lembaga_id) == $l->id ? 'selected' : '' }}>
                                        {{ $l->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pemilik_lembaga_id')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                            <input type="hidden" name="nama_pemilik_lembaga" id="nama_pemilik_lembaga" value="{{ old('nama_pemilik_lembaga', $kendaraan->nama_pemilik_lembaga) }}">
                        </div>
                    </div>
                </div>

                <!-- Lokasi & Status -->
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
                                    <option value="{{ $g->id }}" {{ old('garasi_id', $kendaraan->garasi_id) == $g->id ? 'selected' : '' }}>
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
                                value="{{ old('pemegang_nama', $kendaraan->pemegang_nama) }}"
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
                                <option value="aktif" {{ old('status', $kendaraan->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $kendaraan->status) === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                                <option value="dihibahkan" {{ old('status', $kendaraan->status) === 'dihibahkan' ? 'selected' : '' }}>Dihibahkan</option>
                                <option value="dijual" {{ old('status', $kendaraan->status) === 'dijual' ? 'selected' : '' }}>Dijual</option>
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
                            <input type="date" name="tanggal_perolehan" id="tanggal_perolehan"
                                value="{{ old('tanggal_perolehan', $kendaraan->tanggal_perolehan?->format('Y-m-d')) }}"
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
                            <input type="date" name="tanggal_beli" id="tanggal_beli"
                                value="{{ old('tanggal_beli', $kendaraan->tanggal_beli?->format('Y-m-d')) }}"
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
                                <input type="number" name="harga_beli" id="harga_beli" value="{{ old('harga_beli', $kendaraan->harga_beli) }}" min="0" step="1"
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
                <div id="hibah-section" class="rounded-lg bg-white p-6 dark:bg-darkblack-600 {{ old('status', $kendaraan->status) === 'dihibahkan' ? '' : 'hidden' }}">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Info Hibah</h3>

                    <div class="grid gap-4 md:grid-cols-2">
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

                        <!-- Penerima Hibah -->
                        <div>
                            <label for="nama_penerima_hibah" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Nama Penerima Hibah <span class="text-error-300">*</span>
                            </label>
                            <input type="text" name="nama_penerima_hibah" id="nama_penerima_hibah" value="{{ old('nama_penerima_hibah', $kendaraan->nama_penerima_hibah) }}"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('nama_penerima_hibah') border-error-300 @enderror"
                                placeholder="Nama penerima hibah">
                            @error('nama_penerima_hibah')
                                <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Info Penjualan (conditional) -->
                <div id="jual-section" class="rounded-lg bg-white p-6 dark:bg-darkblack-600 {{ old('status', $kendaraan->status) === 'dijual' ? '' : 'hidden' }}">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Info Penjualan</h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        <!-- Tanggal Jual -->
                        <div>
                            <label for="tanggal_jual" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Tanggal Jual
                            </label>
                            <input type="date" name="tanggal_jual" id="tanggal_jual"
                                value="{{ old('tanggal_jual', $kendaraan->tanggal_jual?->format('Y-m-d')) }}"
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
                                <input type="number" name="harga_jual" id="harga_jual" value="{{ old('harga_jual', $kendaraan->harga_jual) }}" min="0" step="1"
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
                            <input type="text" name="nama_pembeli" id="nama_pembeli" value="{{ old('nama_pembeli', $kendaraan->nama_pembeli) }}"
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
                                <input type="checkbox" name="is_dipinjam" id="is_dipinjam" value="1" {{ old('is_dipinjam', $kendaraan->is_dipinjam) ? 'checked' : '' }}
                                    class="h-5 w-5 rounded border-bgray-300 text-success-300 focus:ring-success-300">
                                <span class="text-sm font-medium text-bgray-900 dark:text-white">Sedang Dipinjam</span>
                            </label>
                        </div>

                        <!-- Pinjam Fields (conditional) -->
                        <div id="pinjam-fields" class="md:col-span-2 {{ old('is_dipinjam', $kendaraan->is_dipinjam) ? '' : 'hidden' }}">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="dipinjam_paroki_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                        Dipinjam Oleh (Paroki)
                                    </label>
                                    <select name="dipinjam_paroki_id" id="dipinjam_paroki_id"
                                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('dipinjam_paroki_id') border-error-300 @enderror">
                                        <option value="">Pilih Paroki</option>
                                        @foreach($paroki as $p)
                                            <option value="{{ $p->id }}" {{ old('dipinjam_paroki_id', $kendaraan->dipinjam_paroki_id) == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama }} ({{ $p->kevikepan->nama ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dipinjam_paroki_id')
                                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                                    @enderror
                                    <input type="hidden" name="dipinjam_oleh" id="dipinjam_oleh" value="{{ old('dipinjam_oleh', $kendaraan->dipinjam_oleh) }}">
                                </div>

                                <div>
                                    <label for="tanggal_pinjam" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                        Tanggal Pinjam
                                    </label>
                                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                                        value="{{ old('tanggal_pinjam', $kendaraan->tanggal_pinjam?->format('Y-m-d')) }}"
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
                                <input type="checkbox" name="is_tarikan" id="is_tarikan" value="1" {{ old('is_tarikan', $kendaraan->is_tarikan) ? 'checked' : '' }}
                                    class="h-5 w-5 rounded border-bgray-300 text-success-300 focus:ring-success-300">
                                <span class="text-sm font-medium text-bgray-900 dark:text-white">Kendaraan Tarikan</span>
                            </label>
                            <p class="mt-1 text-xs text-bgray-500">Centang jika kendaraan ini merupakan tarikan dari tempat lain</p>
                        </div>

                        <!-- Tarikan Fields (conditional) -->
                        <div id="tarikan-fields" class="md:col-span-2 {{ old('is_tarikan', $kendaraan->is_tarikan) ? '' : 'hidden' }}">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="tarikan_paroki_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                        Ditarik Dari Paroki
                                    </label>
                                    <select name="tarikan_paroki_id" id="tarikan_paroki_id"
                                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tarikan_paroki_id') border-error-300 @enderror">
                                        <option value="">- Tidak Ada -</option>
                                        @foreach($paroki as $p)
                                            <option value="{{ $p->id }}" {{ old('tarikan_paroki_id', $kendaraan->tarikan_paroki_id) == $p->id ? 'selected' : '' }}>
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
                                            <option value="{{ $l->id }}" {{ old('tarikan_lembaga_id', $kendaraan->tarikan_lembaga_id) == $l->id ? 'selected' : '' }}>
                                                {{ $l->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tarikan_lembaga_id')
                                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                                    @enderror
                                    <input type="hidden" name="tarikan_dari" id="tarikan_dari" value="{{ old('tarikan_dari', $kendaraan->tarikan_dari) }}">
                                </div>

                                <div>
                                    <label for="tarikan_pemakai" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                        Pengguna Sebelumnya
                                    </label>
                                    <input type="text" name="tarikan_pemakai" id="tarikan_pemakai" value="{{ old('tarikan_pemakai', $kendaraan->tarikan_pemakai) }}"
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
                                        placeholder="Deskripsi kondisi kendaraan saat ditarik">{{ old('tarikan_kondisi', $kendaraan->tarikan_kondisi) }}</textarea>
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
                        <!-- Existing riwayat from database -->
                        @foreach($kendaraan->riwayatPemakai as $index => $riwayat)
                            <div class="riwayat-row rounded-lg border border-bgray-200 p-4 dark:border-darkblack-400">
                                <div class="mb-4 flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-bgray-700 dark:text-bgray-200">
                                        {{ $riwayat->nama_pemakai }}
                                        @if($riwayat->isAktif())
                                            <span class="ml-2 inline-flex rounded-full bg-success-50 px-2 py-0.5 text-xs font-medium text-success-300">Aktif</span>
                                        @endif
                                    </h4>
                                    <div class="flex items-center gap-2">
                                        <label class="flex cursor-pointer items-center gap-1 text-error-300 hover:text-error-400">
                                            <input type="checkbox" name="delete_riwayat[]" value="{{ $riwayat->id }}"
                                                class="h-4 w-4 rounded border-error-300 text-error-300">
                                            <span class="text-xs">Hapus</span>
                                        </label>
                                    </div>
                                </div>
                                <input type="hidden" name="riwayat_pemakai[{{ $index }}][id]" value="{{ $riwayat->id }}">
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Jenis Pengguna</label>
                                        <select name="riwayat_pemakai[{{ $index }}][jenis_pemakai]" class="jenis-pemakai-select w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                            <option value="paroki" {{ old("riwayat_pemakai.$index.jenis_pemakai", $riwayat->jenis_pemakai) === 'paroki' ? 'selected' : '' }}>Paroki</option>
                                            <option value="lembaga" {{ old("riwayat_pemakai.$index.jenis_pemakai", $riwayat->jenis_pemakai) === 'lembaga' ? 'selected' : '' }}>Lembaga</option>
                                            <option value="pribadi" {{ old("riwayat_pemakai.$index.jenis_pemakai", $riwayat->jenis_pemakai) === 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                                        </select>
                                    </div>
                                    <div class="riwayat-paroki-field {{ old("riwayat_pemakai.$index.jenis_pemakai", $riwayat->jenis_pemakai) === 'paroki' ? '' : 'hidden' }}">
                                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Paroki</label>
                                        <select name="riwayat_pemakai[{{ $index }}][paroki_id]" class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                            <option value="">Pilih Paroki</option>
                                            @foreach($paroki as $p)
                                                <option value="{{ $p->id }}" {{ old("riwayat_pemakai.$index.paroki_id", $riwayat->paroki_id) == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="riwayat-lembaga-field {{ old("riwayat_pemakai.$index.jenis_pemakai", $riwayat->jenis_pemakai) === 'lembaga' ? '' : 'hidden' }}">
                                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Lembaga</label>
                                        <select name="riwayat_pemakai[{{ $index }}][lembaga_id]" class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                            <option value="">Pilih Lembaga</option>
                                            @foreach($lembaga as $l)
                                                <option value="{{ $l->id }}" {{ old("riwayat_pemakai.$index.lembaga_id", $riwayat->lembaga_id) == $l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="riwayat-pribadi-field {{ old("riwayat_pemakai.$index.jenis_pemakai", $riwayat->jenis_pemakai) === 'pribadi' ? '' : 'hidden' }}">
                                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Nama Pengguna</label>
                                        <input type="text" name="riwayat_pemakai[{{ $index }}][nama_pemakai]" value="{{ old("riwayat_pemakai.$index.nama_pemakai", $riwayat->nama_pemakai) }}"
                                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                                            placeholder="Nama pribadi">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Tanggal Mulai</label>
                                        <input type="date" name="riwayat_pemakai[{{ $index }}][tanggal_mulai]" value="{{ old("riwayat_pemakai.$index.tanggal_mulai", $riwayat->tanggal_mulai?->format('Y-m-d')) }}"
                                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Tanggal Selesai</label>
                                        <input type="date" name="riwayat_pemakai[{{ $index }}][tanggal_selesai]" value="{{ old("riwayat_pemakai.$index.tanggal_selesai", $riwayat->tanggal_selesai?->format('Y-m-d')) }}"
                                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Catatan</label>
                                        <textarea name="riwayat_pemakai[{{ $index }}][catatan]" rows="2"
                                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                                            placeholder="Catatan tentang penggunaan">{{ old("riwayat_pemakai.$index.catatan", $riwayat->catatan) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <p id="riwayat-empty" class="text-center text-sm text-bgray-500 py-8 {{ $kendaraan->riwayatPemakai->count() > 0 ? 'hidden' : '' }}">
                        Belum ada riwayat pengguna. Klik "Tambah Riwayat" untuk menambahkan.
                    </p>
                </div>

                <!-- Catatan -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Catatan</h3>

                    <div>
                        <textarea name="catatan" id="catatan" rows="3"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('catatan') border-error-300 @enderror"
                            placeholder="Catatan tambahan tentang kendaraan">{{ old('catatan', $kendaraan->catatan) }}</textarea>
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
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-bgray-900 dark:text-white">Foto Saat Ini</h3>
                            <span class="text-xs text-bgray-500"><i class="fa fa-arrows-alt mr-1"></i> Drag untuk ubah urutan</span>
                        </div>
                        <div id="gallery-sortable" class="grid grid-cols-2 gap-2">
                            @foreach($kendaraan->gambar->sortBy('urutan') as $gambar)
                                <div class="gallery-item group relative aspect-square overflow-hidden rounded-lg bg-bgray-100 cursor-move" data-id="{{ $gambar->id }}">
                                    <img src="{{ $gambar->url }}" class="h-full w-full object-cover pointer-events-none">
                                    <!-- Overlay dengan tombol aksi -->
                                    <div class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-2 bg-black/50 opacity-0 transition-opacity group-hover:opacity-100">
                                        <button type="button" onclick="event.stopPropagation(); setAsAvatar({{ $gambar->id }})"
                                            class="rounded-lg bg-success-300 px-3 py-1.5 text-xs font-medium text-white hover:bg-success-400">
                                            <i class="fa fa-star mr-1"></i> Jadikan Avatar
                                        </button>
                                        <button type="button" onclick="event.stopPropagation(); toggleDelete(this, {{ $gambar->id }})"
                                            class="flex items-center rounded-lg bg-error-300 px-3 py-1.5 text-xs font-medium text-white hover:bg-error-400">
                                            <i class="fa fa-trash mr-1"></i> Hapus
                                        </button>
                                        <input type="checkbox" name="delete_gambar[]" value="{{ $gambar->id }}" class="hidden delete-checkbox">
                                    </div>
                                    <!-- Delete indicator (clickable to cancel) -->
                                    <div class="delete-indicator absolute right-1 top-1 z-20 hidden cursor-pointer rounded-full bg-error-300 p-2 hover:bg-error-200"
                                         onclick="event.stopPropagation(); cancelDelete(this)"
                                         title="Klik untuk batal hapus">
                                        <i class="fa fa-times text-sm text-white"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-2 text-xs text-bgray-500">
                            Hover gambar untuk aksi. Gambar dengan tanda <span class="text-error-300">X merah</span> akan dihapus saat klik <strong>Simpan</strong>.
                            Klik tanda X untuk membatalkan.
                        </p>
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
        let riwayatIndex = {{ $kendaraan->riwayatPemakai->count() }};

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

        // Toggle delete for gallery images
        function toggleDelete(btn, gambarId) {
            const galleryItem = btn.closest('.gallery-item');
            const checkbox = galleryItem.querySelector('.delete-checkbox');
            const indicator = galleryItem.querySelector('.delete-indicator');

            checkbox.checked = !checkbox.checked;
            indicator.classList.toggle('hidden', !checkbox.checked);

            // Update button text
            if (checkbox.checked) {
                btn.innerHTML = '<i class="fa fa-undo mr-1"></i> Batal Hapus';
                btn.classList.remove('bg-error-300', 'hover:bg-error-400');
                btn.classList.add('bg-warning-300', 'hover:bg-warning-200');
            } else {
                btn.innerHTML = '<i class="fa fa-trash mr-1"></i> Hapus';
                btn.classList.remove('bg-warning-300', 'hover:bg-warning-200');
                btn.classList.add('bg-error-300', 'hover:bg-error-400');
            }
        }

        // Cancel delete by clicking the X indicator
        function cancelDelete(indicator) {
            const galleryItem = indicator.closest('.gallery-item');
            const checkbox = galleryItem.querySelector('.delete-checkbox');
            const hapusBtn = galleryItem.querySelector('button:has(.fa-undo), button:has(.fa-trash)');

            // Uncheck and hide indicator
            checkbox.checked = false;
            indicator.classList.add('hidden');

            // Reset button if found
            if (hapusBtn) {
                hapusBtn.innerHTML = '<i class="fa fa-trash mr-1"></i> Hapus';
                hapusBtn.classList.remove('bg-warning-300', 'hover:bg-warning-200');
                hapusBtn.classList.add('bg-error-300', 'hover:bg-error-400');
            }
        }

        // Drag and Drop Sortable
        const gallerySortable = document.getElementById('gallery-sortable');
        if (gallerySortable) {
            let draggedItem = null;

            gallerySortable.querySelectorAll('.gallery-item').forEach(item => {
                item.setAttribute('draggable', 'true');

                item.addEventListener('dragstart', function(e) {
                    draggedItem = this;
                    this.classList.add('opacity-50');
                    e.dataTransfer.effectAllowed = 'move';
                });

                item.addEventListener('dragend', function(e) {
                    this.classList.remove('opacity-50');
                    draggedItem = null;
                    saveGalleryOrder();
                });

                item.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                });

                item.addEventListener('dragenter', function(e) {
                    e.preventDefault();
                    if (this !== draggedItem) {
                        this.classList.add('ring-2', 'ring-success-300');
                    }
                });

                item.addEventListener('dragleave', function(e) {
                    this.classList.remove('ring-2', 'ring-success-300');
                });

                item.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('ring-2', 'ring-success-300');

                    if (this !== draggedItem) {
                        const allItems = [...gallerySortable.querySelectorAll('.gallery-item')];
                        const draggedIdx = allItems.indexOf(draggedItem);
                        const droppedIdx = allItems.indexOf(this);

                        if (draggedIdx < droppedIdx) {
                            this.parentNode.insertBefore(draggedItem, this.nextSibling);
                        } else {
                            this.parentNode.insertBefore(draggedItem, this);
                        }
                    }
                });
            });
        }

        // Save gallery order via AJAX
        function saveGalleryOrder() {
            const items = document.querySelectorAll('#gallery-sortable .gallery-item');
            const order = Array.from(items).map(item => item.dataset.id);

            fetch('{{ route("kendaraan.gambar.reorder", $kendaraan) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ order: order })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Optional: show success toast
                    console.log('Urutan gambar berhasil disimpan');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Set image as avatar
        function setAsAvatar(gambarId) {
            if (!confirm('Jadikan gambar ini sebagai foto utama (avatar)?')) return;

            fetch('{{ url("kendaraan/" . $kendaraan->id . "/gambar") }}/' + gambarId + '/set-avatar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update avatar preview
                    const avatarPreview = document.getElementById('avatar-preview');
                    avatarPreview.innerHTML = '<img src="' + data.avatar_url + '" class="h-full w-full object-cover">';
                    alert('Avatar berhasil diperbarui!');
                } else {
                    alert('Gagal: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengubah avatar');
            });
        }
    </script>
    @endpush
</x-app-layout>

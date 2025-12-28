<x-app-layout>
    <x-slot name="title">Tambah Servis</x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('servis.index') }}"
                class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Tambah Servis</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">Catat data servis kendaraan</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="POST" action="{{ route('servis.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <!-- Kendaraan -->
                <div>
                    <label for="kendaraan_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Kendaraan <span class="text-error-300">*</span>
                    </label>
                    <select name="kendaraan_id" id="kendaraan_id" required
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('kendaraan_id') border-error-300 @enderror">
                        <option value="">Pilih Kendaraan</option>
                        @foreach($kendaraan as $k)
                            <option value="{{ $k->id }}" {{ old('kendaraan_id', $selectedKendaraanId) == $k->id ? 'selected' : '' }}>
                                {{ $k->plat_nomor }} - {{ $k->merk->nama ?? '' }} {{ $k->nama_model }}
                            </option>
                        @endforeach
                    </select>
                    @error('kendaraan_id')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <!-- Jenis -->
                    <div>
                        <label for="jenis" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Jenis Servis <span class="text-error-300">*</span>
                        </label>
                        <select name="jenis" id="jenis" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                            <option value="rutin" {{ old('jenis') == 'rutin' ? 'selected' : '' }}>Servis Rutin</option>
                            <option value="perbaikan" {{ old('jenis') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                            <option value="darurat" {{ old('jenis') == 'darurat' ? 'selected' : '' }}>Perbaikan Darurat</option>
                            <option value="overhaul" {{ old('jenis') == 'overhaul' ? 'selected' : '' }}>Overhaul</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Status <span class="text-error-300">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                            <option value="dijadwalkan" {{ old('status', 'dijadwalkan') == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                            <option value="dalam_proses" {{ old('status') == 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <!-- Tanggal Servis -->
                    <div>
                        <label for="tanggal_servis" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Tanggal Servis <span class="text-error-300">*</span>
                        </label>
                        <input type="date" name="tanggal_servis" id="tanggal_servis" value="{{ old('tanggal_servis', now()->format('Y-m-d')) }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tanggal_servis') border-error-300 @enderror">
                        @error('tanggal_servis')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label for="tanggal_selesai" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Tanggal Selesai
                        </label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>

                    <!-- Kilometer -->
                    <div>
                        <label for="kilometer" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Kilometer Saat Servis
                        </label>
                        <input type="number" name="kilometer" id="kilometer" value="{{ old('kilometer') }}" min="0"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="Contoh: 50000">
                    </div>

                    <!-- Biaya -->
                    <div>
                        <label for="biaya" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Biaya (Rp)
                        </label>
                        <input type="number" name="biaya" id="biaya" value="{{ old('biaya') }}" min="0"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="0">
                    </div>
                </div>

                <!-- Bengkel -->
                <div>
                    <label for="bengkel" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Nama Bengkel
                    </label>
                    <input type="text" name="bengkel" id="bengkel" value="{{ old('bengkel') }}"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                        placeholder="Nama bengkel atau tempat servis">
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Deskripsi Pekerjaan <span class="text-error-300">*</span>
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" required
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('deskripsi') border-error-300 @enderror"
                        placeholder="Jelaskan pekerjaan yang dilakukan...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Spare Parts -->
                <div>
                    <label for="spare_parts" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Spare Parts / Komponen
                    </label>
                    <textarea name="spare_parts" id="spare_parts" rows="2"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                        placeholder="Daftar spare parts yang diganti...">{{ old('spare_parts') }}</textarea>
                </div>

                <!-- Next Service Info -->
                <div class="rounded-lg border border-bgray-200 p-4 dark:border-darkblack-400">
                    <h3 class="mb-4 font-medium text-bgray-900 dark:text-white">Jadwal Servis Berikutnya (Opsional)</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="servis_berikutnya" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                Tanggal Servis Berikutnya
                            </label>
                            <input type="date" name="servis_berikutnya" id="servis_berikutnya" value="{{ old('servis_berikutnya') }}"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        </div>
                        <div>
                            <label for="km_berikutnya" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                                KM Servis Berikutnya
                            </label>
                            <input type="number" name="km_berikutnya" id="km_berikutnya" value="{{ old('km_berikutnya') }}" min="0"
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                                placeholder="Contoh: 55000">
                        </div>
                    </div>
                </div>

                <!-- Upload Bukti -->
                <div>
                    <label for="bukti" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Upload Bukti / Nota
                    </label>
                    <input type="file" name="bukti" id="bukti" accept="image/*,.pdf"
                        class="w-full text-sm text-bgray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-accent-300 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-accent-400">
                    <p class="mt-1 text-xs text-bgray-500">JPG, PNG, atau PDF. Maks 2MB.</p>
                </div>

                <!-- Catatan -->
                <div>
                    <label for="catatan" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Catatan Tambahan
                    </label>
                    <textarea name="catatan" id="catatan" rows="2"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                        placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-4">
                <button type="submit"
                    class="rounded-lg bg-accent-300 px-6 py-3 font-semibold text-white transition-all hover:bg-accent-400">
                    <i class="fa fa-save mr-2"></i> Simpan
                </button>
                <a href="{{ route('servis.index') }}"
                    class="rounded-lg border border-bgray-300 px-6 py-3 font-semibold text-bgray-600 hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="title">Tambah Pajak</x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('pajak.index') }}"
                class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Tambah Pajak</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">Catat data pajak kendaraan</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="POST" action="{{ route('pajak.store') }}" enctype="multipart/form-data">
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
                            Jenis Pajak <span class="text-error-300">*</span>
                        </label>
                        <select name="jenis" id="jenis" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                            <option value="tahunan" {{ old('jenis') == 'tahunan' ? 'selected' : '' }}>Pajak Tahunan</option>
                            <option value="lima_tahunan" {{ old('jenis') == 'lima_tahunan' ? 'selected' : '' }}>Pajak 5 Tahunan (Ganti Plat)</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Status <span class="text-error-300">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                            <option value="belum_bayar" {{ old('status', 'belum_bayar') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>

                    <!-- Tanggal Jatuh Tempo -->
                    <div>
                        <label for="tanggal_jatuh_tempo" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Tanggal Jatuh Tempo <span class="text-error-300">*</span>
                        </label>
                        <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tanggal_jatuh_tempo') border-error-300 @enderror">
                        @error('tanggal_jatuh_tempo')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Bayar -->
                    <div>
                        <label for="tanggal_bayar" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Tanggal Bayar
                        </label>
                        <input type="date" name="tanggal_bayar" id="tanggal_bayar" value="{{ old('tanggal_bayar') }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>

                    <!-- Nominal -->
                    <div>
                        <label for="nominal" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Nominal Pajak (Rp)
                        </label>
                        <input type="number" name="nominal" id="nominal" value="{{ old('nominal') }}" min="0"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="0">
                    </div>

                    <!-- Denda -->
                    <div>
                        <label for="denda" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Denda (Rp)
                        </label>
                        <input type="number" name="denda" id="denda" value="{{ old('denda', 0) }}" min="0"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="0">
                    </div>
                </div>

                <!-- Nomor Notice -->
                <div>
                    <label for="nomor_notice" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Nomor Notice/Bukti Bayar
                    </label>
                    <input type="text" name="nomor_notice" id="nomor_notice" value="{{ old('nomor_notice') }}"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                        placeholder="Nomor bukti pembayaran">
                </div>

                <!-- Upload Bukti -->
                <div>
                    <label for="bukti" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Upload Bukti Bayar
                    </label>
                    <input type="file" name="bukti" id="bukti" accept="image/*,.pdf"
                        class="w-full text-sm text-bgray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-accent-300 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-accent-400">
                    <p class="mt-1 text-xs text-bgray-500">JPG, PNG, atau PDF. Maks 2MB.</p>
                </div>

                <!-- Catatan -->
                <div>
                    <label for="catatan" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Catatan
                    </label>
                    <textarea name="catatan" id="catatan" rows="3"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                        placeholder="Catatan tambahan">{{ old('catatan') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-4">
                <button type="submit"
                    class="rounded-lg bg-accent-300 px-6 py-3 font-semibold text-white transition-all hover:bg-accent-400">
                    <i class="fa fa-save mr-2"></i> Simpan
                </button>
                <a href="{{ route('pajak.index') }}"
                    class="rounded-lg border border-bgray-300 px-6 py-3 font-semibold text-bgray-600 hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

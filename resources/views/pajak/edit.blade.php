<x-app-layout>
    <x-slot name="title">Edit Pajak</x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('pajak.show', $pajak) }}"
                class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Edit Pajak</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">{{ $pajak->kendaraan->plat_nomor }}</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="POST" action="{{ route('pajak.update', $pajak) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Kendaraan -->
                <div>
                    <label for="kendaraan_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Kendaraan <span class="text-error-300">*</span>
                    </label>
                    <select name="kendaraan_id" id="kendaraan_id" required
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        @foreach($kendaraan as $k)
                            <option value="{{ $k->id }}" {{ old('kendaraan_id', $pajak->kendaraan_id) == $k->id ? 'selected' : '' }}>
                                {{ $k->plat_nomor }} - {{ $k->merk->nama ?? '' }} {{ $k->nama_model }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="jenis" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Jenis Pajak <span class="text-error-300">*</span>
                        </label>
                        <select name="jenis" id="jenis" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                            <option value="tahunan" {{ old('jenis', $pajak->jenis) == 'tahunan' ? 'selected' : '' }}>Pajak Tahunan</option>
                            <option value="lima_tahunan" {{ old('jenis', $pajak->jenis) == 'lima_tahunan' ? 'selected' : '' }}>Pajak 5 Tahunan</option>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Status <span class="text-error-300">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                            <option value="belum_bayar" {{ old('status', $pajak->status) == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="lunas" {{ old('status', $pajak->status) == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="terlambat" {{ old('status', $pajak->status) == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                    </div>

                    <div>
                        <label for="tanggal_jatuh_tempo" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Tanggal Jatuh Tempo <span class="text-error-300">*</span>
                        </label>
                        <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo"
                            value="{{ old('tanggal_jatuh_tempo', $pajak->tanggal_jatuh_tempo?->format('Y-m-d')) }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>

                    <div>
                        <label for="tanggal_bayar" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Tanggal Bayar
                        </label>
                        <input type="date" name="tanggal_bayar" id="tanggal_bayar"
                            value="{{ old('tanggal_bayar', $pajak->tanggal_bayar?->format('Y-m-d')) }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>

                    <div>
                        <label for="nominal" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Nominal Pajak (Rp)
                        </label>
                        <input type="number" name="nominal" id="nominal" value="{{ old('nominal', $pajak->nominal) }}" min="0"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>

                    <div>
                        <label for="denda" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Denda (Rp)
                        </label>
                        <input type="number" name="denda" id="denda" value="{{ old('denda', $pajak->denda) }}" min="0"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>
                </div>

                <div>
                    <label for="nomor_notice" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Nomor Notice
                    </label>
                    <input type="text" name="nomor_notice" id="nomor_notice" value="{{ old('nomor_notice', $pajak->nomor_notice) }}"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                </div>

                <div>
                    <label for="bukti" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Upload Bukti Baru
                    </label>
                    @if($pajak->bukti_path)
                        <p class="mb-2 text-sm text-bgray-500">
                            <a href="{{ $pajak->bukti_url }}" target="_blank" class="text-success-300 hover:underline">
                                <i class="fa fa-file mr-1"></i> Lihat bukti saat ini
                            </a>
                        </p>
                    @endif
                    <input type="file" name="bukti" id="bukti" accept="image/*,.pdf"
                        class="w-full text-sm text-bgray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-success-300 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
                </div>

                <div>
                    <label for="catatan" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Catatan
                    </label>
                    <textarea name="catatan" id="catatan" rows="3"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">{{ old('catatan', $pajak->catatan) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-4">
                <button type="submit"
                    class="rounded-lg bg-success-300 px-6 py-3 font-semibold text-white transition-all hover:bg-success-400">
                    <i class="fa fa-save mr-2"></i> Simpan
                </button>
                <a href="{{ route('pajak.show', $pajak) }}"
                    class="rounded-lg border border-bgray-300 px-6 py-3 font-semibold text-bgray-600 hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

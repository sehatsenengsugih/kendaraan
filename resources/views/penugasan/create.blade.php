<x-app-layout>
    <x-slot name="title">Tambah Penugasan</x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('penugasan.index') }}"
                class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Tambah Penugasan</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">Tugaskan kendaraan ke pemegang</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="POST" action="{{ route('penugasan.store') }}">
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
                    <p class="mt-1 text-xs text-bgray-500">Pastikan kendaraan tidak memiliki penugasan aktif</p>
                </div>

                <!-- Pemegang -->
                <div>
                    <label for="pemegang_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Pemegang <span class="text-error-300">*</span>
                    </label>
                    <select name="pemegang_id" id="pemegang_id" required
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('pemegang_id') border-error-300 @enderror">
                        <option value="">Pilih Pemegang</option>
                        @foreach($pemegang as $p)
                            <option value="{{ $p->id }}" {{ old('pemegang_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->lembaga ?? $p->type }})
                            </option>
                        @endforeach
                    </select>
                    @error('pemegang_id')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="tanggal_mulai" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Tanggal Mulai <span class="text-error-300">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', now()->format('Y-m-d')) }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('tanggal_mulai') border-error-300 @enderror">
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label for="tanggal_selesai" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Tanggal Selesai (Opsional)
                        </label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        <p class="mt-1 text-xs text-bgray-500">Kosongkan jika belum ditentukan</p>
                    </div>
                </div>

                <!-- Tujuan -->
                <div>
                    <label for="tujuan" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Tujuan Penugasan
                    </label>
                    <textarea name="tujuan" id="tujuan" rows="3"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                        placeholder="Jelaskan tujuan penugasan kendaraan...">{{ old('tujuan') }}</textarea>
                </div>

                <!-- Catatan -->
                <div>
                    <label for="catatan" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Catatan
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
                <a href="{{ route('penugasan.index') }}"
                    class="rounded-lg border border-bgray-300 px-6 py-3 font-semibold text-bgray-600 hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

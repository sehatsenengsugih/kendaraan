<x-app-layout>
    <x-slot name="title">Edit Penugasan</x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('penugasan.show', $penugasan) }}"
                class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Edit Penugasan</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">{{ $penugasan->kendaraan->plat_nomor }}</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="POST" action="{{ route('penugasan.update', $penugasan) }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Kendaraan (Read Only) -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Kendaraan
                    </label>
                    <div class="rounded-lg border border-bgray-200 bg-bgray-50 px-4 py-3 text-bgray-700 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        {{ $penugasan->kendaraan->plat_nomor }} - {{ $penugasan->kendaraan->merk->nama ?? '' }} {{ $penugasan->kendaraan->nama_model }}
                    </div>
                    <p class="mt-1 text-xs text-bgray-500">Kendaraan tidak dapat diubah. Hapus penugasan untuk memilih kendaraan lain.</p>
                </div>

                <!-- Pemegang -->
                <div>
                    <label for="pemegang_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Pemegang <span class="text-error-300">*</span>
                    </label>
                    <select name="pemegang_id" id="pemegang_id" required
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        @foreach($pemegang as $p)
                            <option value="{{ $p->id }}" {{ old('pemegang_id', $penugasan->pemegang_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->lembaga ?? $p->type }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="tanggal_mulai" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Tanggal Mulai <span class="text-error-300">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                            value="{{ old('tanggal_mulai', $penugasan->tanggal_mulai?->format('Y-m-d')) }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label for="tanggal_selesai" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Tanggal Selesai
                        </label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                            value="{{ old('tanggal_selesai', $penugasan->tanggal_selesai?->format('Y-m-d')) }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Status <span class="text-error-300">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        <option value="aktif" {{ old('status', $penugasan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ old('status', $penugasan->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ old('status', $penugasan->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <p class="mt-1 text-xs text-bgray-500">Jika status diubah ke Selesai/Dibatalkan, pemegang kendaraan akan dikosongkan.</p>
                </div>

                <!-- Tujuan -->
                <div>
                    <label for="tujuan" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Tujuan Penugasan
                    </label>
                    <textarea name="tujuan" id="tujuan" rows="3"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">{{ old('tujuan', $penugasan->tujuan) }}</textarea>
                </div>

                <!-- Catatan -->
                <div>
                    <label for="catatan" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Catatan
                    </label>
                    <textarea name="catatan" id="catatan" rows="2"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">{{ old('catatan', $penugasan->catatan) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-4">
                <button type="submit"
                    class="rounded-lg bg-success-300 px-6 py-3 font-semibold text-white transition-all hover:bg-success-400">
                    <i class="fa fa-save mr-2"></i> Simpan
                </button>
                <a href="{{ route('penugasan.show', $penugasan) }}"
                    class="rounded-lg border border-bgray-300 px-6 py-3 font-semibold text-bgray-600 hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

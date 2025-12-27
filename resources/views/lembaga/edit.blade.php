<x-app-layout>
    <x-slot name="title">Edit Lembaga</x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('lembaga.index') }}"
                class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Edit Lembaga</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">{{ $lembaga->nama }}</p>
            </div>
        </div>
    </x-slot>

    <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="POST" action="{{ route('lembaga.update', $lembaga) }}">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <!-- Nama Lembaga -->
                <div class="md:col-span-2">
                    <label for="nama" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Nama Lembaga <span class="text-error-300">*</span>
                    </label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $lembaga->nama) }}" required
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('nama') border-error-300 @enderror"
                        placeholder="Contoh: Yayasan Kanisius">
                    @error('nama')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kota -->
                <div>
                    <label for="kota" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Kota
                    </label>
                    <input type="text" name="kota" id="kota" value="{{ old('kota', $lembaga->kota) }}"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('kota') border-error-300 @enderror"
                        placeholder="Contoh: Semarang">
                    @error('kota')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telepon -->
                <div>
                    <label for="telepon" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        No. Telepon
                    </label>
                    <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $lembaga->telepon) }}"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('telepon') border-error-300 @enderror"
                        placeholder="Contoh: 024-1234567">
                    @error('telepon')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label for="alamat" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Alamat
                    </label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('alamat') border-error-300 @enderror"
                        placeholder="Masukkan alamat lengkap lembaga">{{ old('alamat', $lembaga->alamat) }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $lembaga->email) }}"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('email') border-error-300 @enderror"
                        placeholder="Contoh: info@lembaga.org">
                    @error('email')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $lembaga->is_active) ? 'checked' : '' }}
                            class="h-5 w-5 rounded border-bgray-300 text-success-300 focus:ring-success-300">
                        <span class="text-sm font-medium text-bgray-900 dark:text-white">Lembaga Aktif</span>
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex items-center gap-4">
                <button type="submit"
                    class="rounded-lg bg-success-300 px-6 py-3 font-semibold text-white transition-all hover:bg-success-400">
                    <i class="fa fa-save mr-2"></i> Simpan Perubahan
                </button>
                <a href="{{ route('lembaga.index') }}"
                    class="rounded-lg border border-bgray-300 px-6 py-3 font-semibold text-bgray-600 transition-all hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

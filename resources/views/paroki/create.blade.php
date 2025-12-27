<x-app-layout>
    <x-slot name="title">Tambah Paroki</x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('paroki.index') }}"
                class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Tambah Paroki</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">Tambah data paroki baru</p>
            </div>
        </div>
    </x-slot>

    <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="POST" action="{{ route('paroki.store') }}">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <!-- Nama Paroki -->
                <div class="md:col-span-2">
                    <label for="nama" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Nama Paroki <span class="text-error-300">*</span>
                    </label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('nama') border-error-300 @enderror"
                        placeholder="Contoh: Paroki St. Yoseph Gedangan">
                    @error('nama')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kevikepan -->
                <div>
                    <label for="kevikepan_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Kevikepan <span class="text-error-300">*</span>
                    </label>
                    <select name="kevikepan_id" id="kevikepan_id" required
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('kevikepan_id') border-error-300 @enderror">
                        <option value="">Pilih Kevikepan</option>
                        @foreach($kevikepan as $kev)
                            <option value="{{ $kev->id }}" {{ old('kevikepan_id') == $kev->id ? 'selected' : '' }}>
                                {{ $kev->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kevikepan_id')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kota -->
                <div>
                    <label for="kota" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Kota
                    </label>
                    <input type="text" name="kota" id="kota" value="{{ old('kota') }}"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('kota') border-error-300 @enderror"
                        placeholder="Contoh: Semarang">
                    @error('kota')
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
                        placeholder="Masukkan alamat lengkap paroki">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telepon -->
                <div>
                    <label for="telepon" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        No. Telepon
                    </label>
                    <input type="text" name="telepon" id="telepon" value="{{ old('telepon') }}"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('telepon') border-error-300 @enderror"
                        placeholder="Contoh: 024-1234567">
                    @error('telepon')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('email') border-error-300 @enderror"
                        placeholder="Contoh: paroki@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked
                            class="h-5 w-5 rounded border-bgray-300 text-success-300 focus:ring-success-300">
                        <span class="text-sm font-medium text-bgray-900 dark:text-white">Paroki Aktif</span>
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex items-center gap-4">
                <button type="submit"
                    class="rounded-lg bg-success-300 px-6 py-3 font-semibold text-white transition-all hover:bg-success-400">
                    <i class="fa fa-save mr-2"></i> Simpan Paroki
                </button>
                <a href="{{ route('paroki.index') }}"
                    class="rounded-lg border border-bgray-300 px-6 py-3 font-semibold text-bgray-600 transition-all hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

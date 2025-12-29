<x-app-layout>
    <x-slot name="title">Tambah Garasi</x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('garasi.index') }}"
                class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Tambah Garasi</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">Tambah lokasi penyimpanan kendaraan baru</p>
            </div>
        </div>
    </x-slot>

    <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="POST" action="{{ route('garasi.store') }}">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <!-- Nama Garasi -->
                <div class="md:col-span-2">
                    <label for="nama" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Nama Garasi <span class="text-error-300">*</span>
                    </label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('nama') border-error-300 @enderror"
                        placeholder="Contoh: Garasi Paroki St. Yoseph Gedangan">
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
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('kevikepan_id') border-error-300 @enderror">
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
                        Kota <span class="text-error-300">*</span>
                    </label>
                    <select name="kota" id="kota" required
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('kota') border-error-300 @enderror">
                        <option value="">Pilih Kota</option>
                        @foreach(\App\Models\Paroki::getKotaList() as $id => $nama)
                            <option value="{{ $nama }}" {{ old('kota') == $nama ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kota')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label for="alamat" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Alamat Lengkap <span class="text-error-300">*</span>
                    </label>
                    <textarea name="alamat" id="alamat" rows="3" required
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('alamat') border-error-300 @enderror"
                        placeholder="Masukkan alamat lengkap garasi">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kode Pos -->
                <div>
                    <label for="kode_pos" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Kode Pos
                    </label>
                    <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos') }}"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('kode_pos') border-error-300 @enderror"
                        placeholder="Contoh: 50211">
                    @error('kode_pos')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <hr class="border-bgray-200 dark:border-darkblack-400">
                </div>

                <h3 class="md:col-span-2 text-lg font-semibold text-bgray-900 dark:text-white">
                    Person In Charge (PIC)
                </h3>

                <!-- PIC Name -->
                <div>
                    <label for="pic_name" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Nama PIC
                    </label>
                    <input type="text" name="pic_name" id="pic_name" value="{{ old('pic_name') }}"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('pic_name') border-error-300 @enderror"
                        placeholder="Nama penanggung jawab garasi">
                    @error('pic_name')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PIC Phone -->
                <div>
                    <label for="pic_phone" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        No. Telepon PIC
                    </label>
                    <input type="text" name="pic_phone" id="pic_phone" value="{{ old('pic_phone') }}"
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('pic_phone') border-error-300 @enderror"
                        placeholder="Contoh: 08123456789">
                    @error('pic_phone')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex items-center gap-4">
                <button type="submit"
                    class="rounded-lg bg-accent-300 px-6 py-3 font-semibold text-white transition-all hover:bg-accent-400">
                    <i class="fa fa-save mr-2"></i> Simpan Garasi
                </button>
                <a href="{{ route('garasi.index') }}"
                    class="rounded-lg border border-bgray-300 px-6 py-3 font-semibold text-bgray-600 transition-all hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

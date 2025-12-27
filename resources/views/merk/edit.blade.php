<x-app-layout>
    <x-slot name="title">Edit Merk</x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('merk.index') }}"
                class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Edit Merk</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">{{ $merk->nama }}</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-xl rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="POST" action="{{ route('merk.update', $merk) }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Nama Merk -->
                <div>
                    <label for="nama" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Nama Merk <span class="text-error-300">*</span>
                    </label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $merk->nama) }}" required
                        class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('nama') border-error-300 @enderror"
                        placeholder="Contoh: Toyota, Honda, Suzuki">
                    @error('nama')
                        <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                        Jenis Kendaraan <span class="text-error-300">*</span>
                    </label>
                    <div class="flex gap-6">
                        <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-bgray-200 px-4 py-3 transition-all hover:border-success-300 dark:border-darkblack-400 @if(old('jenis', $merk->jenis) === 'mobil') border-success-300 bg-success-50 @endif">
                            <input type="radio" name="jenis" value="mobil" {{ old('jenis', $merk->jenis) === 'mobil' ? 'checked' : '' }}
                                class="h-5 w-5 text-success-300 focus:ring-success-300">
                            <span class="flex items-center gap-2 text-bgray-900 dark:text-white">
                                <i class="fa fa-car text-blue-500"></i> Mobil
                            </span>
                        </label>
                        <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-bgray-200 px-4 py-3 transition-all hover:border-success-300 dark:border-darkblack-400 @if(old('jenis', $merk->jenis) === 'motor') border-success-300 bg-success-50 @endif">
                            <input type="radio" name="jenis" value="motor" {{ old('jenis', $merk->jenis) === 'motor' ? 'checked' : '' }}
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
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex items-center gap-4">
                <button type="submit"
                    class="rounded-lg bg-success-300 px-6 py-3 font-semibold text-white transition-all hover:bg-success-400">
                    <i class="fa fa-save mr-2"></i> Simpan Perubahan
                </button>
                <a href="{{ route('merk.index') }}"
                    class="rounded-lg border border-bgray-300 px-6 py-3 font-semibold text-bgray-600 transition-all hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

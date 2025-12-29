<x-app-layout>
    <x-slot name="title">Edit Paroki</x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('paroki.index') }}"
                class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Edit Paroki</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">{{ $paroki->full_name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="POST" action="{{ route('paroki.update', $paroki) }}" x-data="{ statusParoki: '{{ old('status_paroki_id', $paroki->status_paroki_id) }}' }">
            @csrf
            @method('PUT')

            <!-- Informasi Dasar -->
            <div class="mb-8">
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">
                    <i class="fa fa-church mr-2 text-accent-300"></i> Informasi Dasar
                </h3>
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Status Paroki -->
                    <div>
                        <label for="status_paroki_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Tipe <span class="text-error-300">*</span>
                        </label>
                        <select name="status_paroki_id" id="status_paroki_id" required x-model="statusParoki"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('status_paroki_id') border-error-300 @enderror">
                            <option value="1" {{ old('status_paroki_id', $paroki->status_paroki_id) == 1 ? 'selected' : '' }}>Paroki</option>
                            <option value="2" {{ old('status_paroki_id', $paroki->status_paroki_id) == 2 ? 'selected' : '' }}>Quasi Paroki</option>
                            <option value="4" {{ old('status_paroki_id', $paroki->status_paroki_id) == 4 ? 'selected' : '' }}>Stasi</option>
                        </select>
                        @error('status_paroki_id')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode -->
                    <div>
                        <label for="kode" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Kode Paroki
                        </label>
                        <input type="text" name="kode" id="kode" value="{{ old('kode', $paroki->kode) }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('kode') border-error-300 @enderror"
                            placeholder="Kode unik (opsional)">
                        @error('kode')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Gereja -->
                    <div>
                        <label for="nama_gereja" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Nama Gereja
                        </label>
                        <input type="text" name="nama_gereja" id="nama_gereja" value="{{ old('nama_gereja', $paroki->nama_gereja) }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('nama_gereja') border-error-300 @enderror"
                            placeholder="Contoh: Santo Yusuf, Kristus Raja">
                        @error('nama_gereja')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Paroki -->
                    <div>
                        <label for="nama" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Nama Paroki <span class="text-error-300">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $paroki->nama) }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('nama') border-error-300 @enderror"
                            placeholder="Contoh: Gedangan, Randusari">
                        @error('nama')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kevikepan -->
                    <div>
                        <label for="kevikepan_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Kevikepan
                        </label>
                        <select name="kevikepan_id" id="kevikepan_id"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('kevikepan_id') border-error-300 @enderror">
                            <option value="">Pilih Kevikepan</option>
                            @foreach($kevikepan as $kev)
                                <option value="{{ $kev->id }}" {{ old('kevikepan_id', $paroki->kevikepan_id) == $kev->id ? 'selected' : '' }}>
                                    {{ $kev->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kevikepan_id')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Paroki (untuk Stasi) -->
                    <div x-show="statusParoki == '4'" x-transition>
                        <label for="parent_id" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Paroki Induk <span class="text-error-300">*</span>
                        </label>
                        <select name="parent_id" id="parent_id"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('parent_id') border-error-300 @enderror">
                            <option value="">Pilih Paroki Induk</option>
                            @foreach($parokiList as $p)
                                <option value="{{ $p->id }}" {{ old('parent_id', $paroki->parent_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_gereja ? $p->nama_gereja . ' ' : '' }}{{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Alamat & Kontak -->
            <div class="mb-8">
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">
                    <i class="fa fa-map-marker-alt mr-2 text-accent-300"></i> Alamat & Kontak
                </h3>
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label for="alamat" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Alamat
                        </label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('alamat') border-error-300 @enderror"
                            placeholder="Masukkan alamat lengkap paroki">{{ old('alamat', $paroki->alamat) }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telepon -->
                    <div>
                        <label for="telepon" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            No. Telepon
                        </label>
                        <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $paroki->telepon) }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('telepon') border-error-300 @enderror"
                            placeholder="Contoh: 024-1234567">
                        @error('telepon')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fax -->
                    <div>
                        <label for="fax" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            No. Fax
                        </label>
                        <input type="text" name="fax" id="fax" value="{{ old('fax', $paroki->fax) }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('fax') border-error-300 @enderror"
                            placeholder="Contoh: 024-1234568">
                        @error('fax')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Email
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $paroki->email) }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('email') border-error-300 @enderror"
                            placeholder="Contoh: paroki@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Koordinat -->
            <div class="mb-8">
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">
                    <i class="fa fa-globe mr-2 text-accent-300"></i> Koordinat GPS
                </h3>
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Latitude -->
                    <div>
                        <label for="latitude" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Latitude
                        </label>
                        <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $paroki->latitude) }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('latitude') border-error-300 @enderror"
                            placeholder="Contoh: -7.005682">
                        @error('latitude')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label for="longitude" class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Longitude
                        </label>
                        <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $paroki->longitude) }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white @error('longitude') border-error-300 @enderror"
                            placeholder="Contoh: 110.410285">
                        @error('longitude')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $paroki->is_active) ? 'checked' : '' }}
                        class="h-5 w-5 rounded border-bgray-300 text-accent-300 focus:ring-accent-300">
                    <span class="text-sm font-medium text-bgray-900 dark:text-white">Paroki Aktif</span>
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center gap-4">
                <button type="submit"
                    class="rounded-lg bg-accent-300 px-6 py-3 font-semibold text-white transition-all hover:bg-accent-400">
                    <i class="fa fa-save mr-2"></i> Simpan Perubahan
                </button>
                <a href="{{ route('paroki.index') }}"
                    class="rounded-lg border border-bgray-300 px-6 py-3 font-semibold text-bgray-600 transition-all hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

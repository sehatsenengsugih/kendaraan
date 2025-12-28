<x-app-layout title="Edit User">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex items-center space-x-2 text-sm text-bgray-500 dark:text-bgray-400 mb-2">
                    <a href="{{ route('users.index') }}" class="hover:text-accent-300">Manajemen User</a>
                    <span>/</span>
                    <span class="text-bgray-900 dark:text-white">Edit User</span>
                </nav>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Edit User: {{ $user->name }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Form Edit User -->
        <div class="lg:col-span-2">
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600">
            <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Avatar Upload -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-3">Foto Profil</label>
                    <div class="flex items-center gap-6">
                        <div class="relative">
                            <div id="avatar-preview" class="h-24 w-24 overflow-hidden rounded-xl border-2 border-bgray-200 dark:border-darkblack-400 bg-bgray-100 dark:bg-darkblack-500">
                                <img id="preview-img" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                            </div>
                            <label for="avatar" class="absolute -bottom-1 -right-1 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-accent-300 text-white hover:bg-accent-400 transition-colors">
                                <i class="fa fa-camera text-xs"></i>
                            </label>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewAvatar(this)">
                            <p class="text-sm text-bgray-600 dark:text-bgray-400">Klik ikon kamera untuk mengganti foto</p>
                            <p class="text-xs text-bgray-500 dark:text-bgray-400 mt-1">Format: JPG, PNG, WebP. Maks 2MB</p>
                            @if($user->avatar_path)
                            <label class="mt-2 inline-flex items-center gap-2 text-sm text-error-300 cursor-pointer">
                                <input type="checkbox" name="remove_avatar" value="1" class="rounded border-bgray-300 text-error-300 focus:ring-error-300">
                                <span>Hapus foto profil</span>
                            </label>
                            @endif
                        </div>
                    </div>
                    @error('avatar')
                        <p class="mt-2 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Nama <span class="text-error-300">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="Nama lengkap">
                        @error('name')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Email <span class="text-error-300">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="email@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Role <span class="text-error-300">*</span></label>
                        <select name="role" id="role" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="admin_servis" {{ old('role', $user->role) === 'admin_servis' ? 'selected' : '' }}>Admin Servis</option>
                            <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t border-bgray-200 dark:border-darkblack-400 pt-4 mt-4">
                        <p class="text-sm text-bgray-600 dark:text-bgray-400 mb-4">Kosongkan password jika tidak ingin mengubahnya.</p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Password Baru</label>
                        <input type="password" name="password" id="password"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="Minimal 8 karakter (opsional)">
                        @error('password')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('users.index') }}" class="rounded-lg border border-bgray-300 px-6 py-3 text-sm font-medium text-bgray-700 hover:bg-bgray-50 dark:border-darkblack-400 dark:text-bgray-300 dark:hover:bg-darkblack-500 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="rounded-lg bg-accent-300 px-6 py-3 text-sm font-medium text-white hover:bg-accent-400 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
        </div>

        <!-- Sidebar - Kendaraan Assigned -->
        <div class="space-y-6">
            <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600">
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">
                    <i class="fa fa-car mr-2 text-accent-300"></i>Kendaraan Assigned
                </h3>

                @if($kendaraanAssigned->count() > 0)
                    <div class="space-y-3">
                        @foreach($kendaraanAssigned as $kendaraan)
                            <a href="{{ route('kendaraan.show', $kendaraan) }}"
                               class="block rounded-lg border border-bgray-200 p-3 transition-all hover:border-accent-300 hover:bg-accent-50/50 dark:border-darkblack-400 dark:hover:bg-darkblack-500">
                                <div class="flex items-center gap-3">
                                    <!-- Avatar/Icon -->
                                    <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-lg bg-bgray-100 dark:bg-darkblack-500">
                                        @if($kendaraan->avatar_path)
                                            <img src="{{ asset('storage/' . $kendaraan->avatar_path) }}"
                                                 alt="{{ $kendaraan->plat_nomor }}"
                                                 class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-bgray-400">
                                                <i class="fa {{ $kendaraan->jenis === 'motor' ? 'fa-motorcycle' : 'fa-car' }}"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <p class="font-mono text-sm font-medium text-bgray-900 dark:text-white truncate">
                                            {{ $kendaraan->plat_nomor }}
                                        </p>
                                        <p class="text-xs text-bgray-500 dark:text-bgray-400 truncate">
                                            {{ $kendaraan->merk->nama ?? '' }} {{ $kendaraan->nama_model }}
                                        </p>
                                    </div>
                                    <!-- Status Badge -->
                                    <div>
                                        @if($kendaraan->status === 'aktif')
                                            <span class="inline-flex rounded-full bg-accent-50 px-2 py-0.5 text-xs font-medium text-accent-400">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-bgray-100 px-2 py-0.5 text-xs font-medium text-bgray-600">
                                                {{ ucfirst($kendaraan->status) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <p class="mt-3 text-xs text-bgray-500 dark:text-bgray-400">
                        Total: {{ $kendaraanAssigned->count() }} kendaraan
                    </p>
                @else
                    <div class="text-center py-6 text-bgray-500 dark:text-bgray-400">
                        <i class="fa fa-car text-3xl text-bgray-300 mb-2"></i>
                        <p class="text-sm">Belum ada kendaraan yang di-assign</p>
                        <p class="text-xs mt-1">Assign kendaraan melalui menu Edit Kendaraan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
</x-app-layout>

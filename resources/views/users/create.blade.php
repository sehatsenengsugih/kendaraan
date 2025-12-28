<x-app-layout title="Tambah User">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex items-center space-x-2 text-sm text-bgray-500 dark:text-bgray-400 mb-2">
                    <a href="{{ route('users.index') }}" class="hover:text-accent-300">Manajemen User</a>
                    <span>/</span>
                    <span class="text-bgray-900 dark:text-white">Tambah User</span>
                </nav>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Tambah User Baru</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600">
            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Avatar Upload -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-3">Foto Profil</label>
                    <div class="flex items-center gap-6">
                        <div class="relative">
                            <div id="avatar-preview" class="h-24 w-24 overflow-hidden rounded-xl border-2 border-bgray-200 dark:border-darkblack-400 bg-bgray-100 dark:bg-darkblack-500">
                                <img id="preview-img" src="https://ui-avatars.com/api/?name=User&background=22C55E&color=fff&size=96" alt="Avatar" class="h-full w-full object-cover">
                            </div>
                            <label for="avatar" class="absolute -bottom-1 -right-1 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-accent-300 text-white hover:bg-accent-400 transition-colors">
                                <i class="fa fa-camera text-xs"></i>
                            </label>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewAvatar(this)">
                            <p class="text-sm text-bgray-600 dark:text-bgray-400">Upload foto profil (opsional)</p>
                            <p class="text-xs text-bgray-500 dark:text-bgray-400 mt-1">Format: JPG, PNG, WebP. Maks 2MB</p>
                        </div>
                    </div>
                    @error('avatar')
                        <p class="mt-2 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Nama <span class="text-error-300">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="Nama lengkap">
                        @error('name')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Email <span class="text-error-300">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
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
                            <option value="">Pilih Role</option>
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="admin_servis" {{ old('role') === 'admin_servis' ? 'selected' : '' }}>Admin Servis</option>
                            <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Password <span class="text-error-300">*</span></label>
                        <input type="password" name="password" id="password" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Konfirmasi Password <span class="text-error-300">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="Ulangi password">
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('users.index') }}" class="rounded-lg border border-bgray-300 px-6 py-3 text-sm font-medium text-bgray-700 hover:bg-bgray-50 dark:border-darkblack-400 dark:text-bgray-300 dark:hover:bg-darkblack-500 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="rounded-lg bg-accent-300 px-6 py-3 text-sm font-medium text-white hover:bg-accent-400 transition-colors">
                        Simpan User
                    </button>
                </div>
            </form>
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

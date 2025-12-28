<x-app-layout title="Profil Saya">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Profil Saya</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-400 mt-1">Kelola informasi akun Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Profile Information -->
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600">
            <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-4">Informasi Profil</h3>
            <p class="text-sm text-bgray-600 dark:text-bgray-400 mb-6">Perbarui informasi profil dan alamat email akun Anda.</p>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
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
                            <label for="avatar" class="absolute -bottom-1 -right-1 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-success-300 text-white hover:bg-success-400 transition-colors">
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
                        <label for="name" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        @error('name')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        @error('email')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="rounded-lg bg-success-300 px-6 py-3 text-sm font-medium text-white hover:bg-success-400 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Update Password -->
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600">
            <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-4">Ubah Password</h3>
            <p class="text-sm text-bgray-600 dark:text-bgray-400 mb-6">Pastikan akun Anda menggunakan password yang panjang dan acak untuk keamanan.</p>

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        @error('current_password')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Password Baru</label>
                        <input type="password" name="password" id="password" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        @error('password')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="rounded-lg bg-success-300 px-6 py-3 text-sm font-medium text-white hover:bg-success-400 transition-colors">
                            Ubah Password
                        </button>
                    </div>
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

<x-app-layout title="Edit User">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex items-center space-x-2 text-sm text-bgray-500 dark:text-bgray-400 mb-2">
                    <a href="{{ route('users.index') }}" class="hover:text-success-300">Manajemen User</a>
                    <span>/</span>
                    <span class="text-bgray-900 dark:text-white">Edit User</span>
                </nav>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Edit User: {{ $user->name }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Nama <span class="text-error-300">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="Nama lengkap">
                        @error('name')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Email <span class="text-error-300">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="email@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Role <span class="text-error-300">*</span></label>
                        <select name="role" id="role" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
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
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="Minimal 8 karakter (opsional)">
                        @error('password')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('users.index') }}" class="rounded-lg border border-bgray-300 px-6 py-3 text-sm font-medium text-bgray-700 hover:bg-bgray-50 dark:border-darkblack-400 dark:text-bgray-300 dark:hover:bg-darkblack-500 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="rounded-lg bg-success-300 px-6 py-3 text-sm font-medium text-white hover:bg-success-400 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

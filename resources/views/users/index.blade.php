<x-app-layout title="Manajemen User">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Manajemen User</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-400 mt-1">Kelola pengguna sistem</p>
            </div>
            <a href="{{ route('users.create') }}" class="inline-flex items-center rounded-lg bg-accent-300 px-4 py-2 text-sm font-medium text-white hover:bg-accent-400 transition-colors">
                <i class="fa fa-plus mr-2"></i> Tambah User
            </a>
        </div>
    </x-slot>

    <!-- Users Table -->
    <div class="rounded-lg bg-white shadow-sm dark:bg-darkblack-600">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-bgray-50 dark:bg-darkblack-500">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-bgray-600 dark:text-bgray-300">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-bgray-600 dark:text-bgray-300">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-bgray-600 dark:text-bgray-300">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-bgray-600 dark:text-bgray-300">Dibuat</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-bgray-600 dark:text-bgray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-bgray-100 dark:divide-darkblack-400">
                    @forelse($users as $user)
                    <tr class="hover:bg-bgray-50 dark:hover:bg-darkblack-500">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-xl border border-bgray-200 dark:border-darkblack-400">
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-bgray-900 dark:text-white">{{ $user->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-bgray-600 dark:text-bgray-300">{{ $user->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role === 'super_admin')
                                <span class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-200">Super Admin</span>
                            @elseif($user->role === 'admin')
                                <span class="inline-flex items-center rounded-full bg-accent-50 px-3 py-1 text-xs font-medium text-accent-400">Admin</span>
                            @elseif($user->role === 'admin_servis')
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">Admin Servis</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-bgray-100 px-3 py-1 text-xs font-medium text-bgray-600 dark:bg-darkblack-400 dark:text-bgray-300">User</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-bgray-600 dark:text-bgray-300">{{ $user->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('users.edit', $user) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-warning-300 hover:bg-warning-50 dark:hover:bg-darkblack-400" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-error-300 hover:bg-error-50 dark:hover:bg-darkblack-400" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-bgray-300 dark:text-bgray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                <p class="text-bgray-500 dark:text-bgray-400">Belum ada user</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="border-t border-bgray-100 dark:border-darkblack-400 px-6 py-4">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</x-app-layout>

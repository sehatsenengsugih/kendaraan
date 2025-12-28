<x-app-layout>
    <x-slot name="title">Audit Log</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Audit Log</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">Riwayat aktivitas sistem</p>
            </div>
        </div>
    </x-slot>

    <!-- Filter Card -->
    <div class="mb-6 rounded-lg bg-white p-6 dark:bg-darkblack-600">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="grid gap-4 md:grid-cols-6">
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Deskripsi, label..."
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Aksi</label>
                <select name="action"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ ucfirst($action) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Model</label>
                <select name="model_type"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    <option value="">Semua</option>
                    @foreach($modelTypes as $type)
                        <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="rounded-lg bg-accent-300 px-4 py-3 font-semibold text-white transition-all hover:bg-accent-400">
                    <i class="fa fa-search"></i>
                </button>
                <a href="{{ route('audit-logs.index') }}"
                    class="rounded-lg border border-bgray-300 px-4 py-3 text-bgray-600 hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="rounded-lg bg-white dark:bg-darkblack-600">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-bgray-200 dark:border-darkblack-400">
                    <tr class="text-left">
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Waktu</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">User</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Aksi</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Model</th>
                        <th class="px-6 py-4 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Deskripsi</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-bgray-600 dark:text-bgray-50">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="border-b border-bgray-200 last:border-0 dark:border-darkblack-400">
                            <td class="px-6 py-4">
                                <span class="text-bgray-900 dark:text-white">{{ $log->created_at->format('d M Y') }}</span>
                                <p class="text-xs text-bgray-500 dark:text-bgray-50">{{ $log->created_at->format('H:i:s') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-bgray-900 dark:text-white">{{ $log->user_name }}</span>
                                @if($log->ip_address)
                                    <p class="text-xs text-bgray-500 dark:text-bgray-50">{{ $log->ip_address }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                                    @switch($log->action_color)
                                        @case('success')
                                            bg-accent-50 text-accent-400
                                            @break
                                        @case('warning')
                                            bg-warning-50 text-warning-400
                                            @break
                                        @case('error')
                                            bg-error-50 text-error-300
                                            @break
                                        @case('info')
                                            bg-blue-50 text-blue-600
                                            @break
                                        @default
                                            bg-bgray-100 text-bgray-600
                                    @endswitch
                                ">
                                    {{ $log->action_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->model_type)
                                    <span class="inline-flex rounded-full bg-bgray-100 px-2 py-1 text-xs font-medium text-bgray-600 dark:bg-darkblack-500 dark:text-bgray-50">
                                        {{ $log->model_type_short }}
                                    </span>
                                    @if($log->model_label)
                                        <p class="mt-1 text-sm text-bgray-900 dark:text-white">{{ $log->model_label }}</p>
                                    @endif
                                @else
                                    <span class="text-bgray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-bgray-600 dark:text-bgray-50">
                                    {{ Str::limit($log->description, 50) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('audit-logs.show', $log) }}"
                                        class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500"
                                        title="Detail">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-bgray-500 dark:text-bgray-50">
                                <i class="fa fa-history mb-4 text-4xl text-bgray-300"></i>
                                <p>Belum ada aktivitas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="border-t border-bgray-200 px-6 py-4 dark:border-darkblack-400">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="title">Detail Audit Log</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Detail Audit Log</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-50">{{ $auditLog->description }}</p>
            </div>
            <a href="{{ route('audit-logs.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-bgray-300 px-4 py-3 font-semibold text-bgray-600 transition-all hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                <i class="fa fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Info Card -->
        <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
            <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Informasi</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-bgray-100 pb-3 dark:border-darkblack-400">
                    <span class="text-sm text-bgray-500 dark:text-bgray-50">Waktu</span>
                    <span class="text-sm font-medium text-bgray-900 dark:text-white">
                        {{ $auditLog->created_at->format('d M Y H:i:s') }}
                    </span>
                </div>
                <div class="flex items-center justify-between border-b border-bgray-100 pb-3 dark:border-darkblack-400">
                    <span class="text-sm text-bgray-500 dark:text-bgray-50">User</span>
                    <span class="text-sm font-medium text-bgray-900 dark:text-white">
                        {{ $auditLog->user_name }}
                    </span>
                </div>
                <div class="flex items-center justify-between border-b border-bgray-100 pb-3 dark:border-darkblack-400">
                    <span class="text-sm text-bgray-500 dark:text-bgray-50">Aksi</span>
                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium
                        @switch($auditLog->action_color)
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
                        {{ $auditLog->action_label }}
                    </span>
                </div>
                @if($auditLog->model_type)
                    <div class="flex items-center justify-between border-b border-bgray-100 pb-3 dark:border-darkblack-400">
                        <span class="text-sm text-bgray-500 dark:text-bgray-50">Model</span>
                        <span class="text-sm font-medium text-bgray-900 dark:text-white">
                            {{ $auditLog->model_type_short }}
                        </span>
                    </div>
                @endif
                @if($auditLog->model_label)
                    <div class="flex items-center justify-between border-b border-bgray-100 pb-3 dark:border-darkblack-400">
                        <span class="text-sm text-bgray-500 dark:text-bgray-50">Label</span>
                        <span class="text-sm font-medium text-bgray-900 dark:text-white">
                            {{ $auditLog->model_label }}
                        </span>
                    </div>
                @endif
                @if($auditLog->ip_address)
                    <div class="flex items-center justify-between border-b border-bgray-100 pb-3 dark:border-darkblack-400">
                        <span class="text-sm text-bgray-500 dark:text-bgray-50">IP Address</span>
                        <span class="text-sm font-medium text-bgray-900 dark:text-white">
                            {{ $auditLog->ip_address }}
                        </span>
                    </div>
                @endif
                @if($auditLog->user_agent)
                    <div class="border-b border-bgray-100 pb-3 dark:border-darkblack-400">
                        <span class="text-sm text-bgray-500 dark:text-bgray-50">User Agent</span>
                        <p class="mt-1 text-xs text-bgray-600 dark:text-bgray-100">
                            {{ $auditLog->user_agent }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Description Card -->
        <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
            <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Deskripsi</h3>
            <p class="text-bgray-600 dark:text-bgray-50">{{ $auditLog->description ?? '-' }}</p>
        </div>
    </div>

    @if($auditLog->old_values || $auditLog->new_values)
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <!-- Old Values Card -->
            @if($auditLog->old_values)
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">
                        <i class="fa fa-arrow-left mr-2 text-error-300"></i>
                        Nilai Sebelum
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b border-bgray-200 dark:border-darkblack-400">
                                <tr class="text-left">
                                    <th class="px-4 py-2 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Field</th>
                                    <th class="px-4 py-2 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auditLog->old_values as $key => $value)
                                    <tr class="border-b border-bgray-100 last:border-0 dark:border-darkblack-400">
                                        <td class="px-4 py-2 text-sm font-medium text-bgray-900 dark:text-white">
                                            {{ str_replace('_', ' ', ucfirst($key)) }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-bgray-600 dark:text-bgray-50">
                                            @if(is_array($value))
                                                <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                            @elseif(is_null($value))
                                                <span class="italic text-bgray-400">null</span>
                                            @else
                                                {{ $value }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- New Values Card -->
            @if($auditLog->new_values)
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">
                        <i class="fa fa-arrow-right mr-2 text-accent-400"></i>
                        Nilai Sesudah
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b border-bgray-200 dark:border-darkblack-400">
                                <tr class="text-left">
                                    <th class="px-4 py-2 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Field</th>
                                    <th class="px-4 py-2 text-sm font-semibold text-bgray-600 dark:text-bgray-50">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auditLog->new_values as $key => $value)
                                    <tr class="border-b border-bgray-100 last:border-0 dark:border-darkblack-400">
                                        <td class="px-4 py-2 text-sm font-medium text-bgray-900 dark:text-white">
                                            {{ str_replace('_', ' ', ucfirst($key)) }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-bgray-600 dark:text-bgray-50">
                                            @if(is_array($value))
                                                <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                            @elseif(is_null($value))
                                                <span class="italic text-bgray-400">null</span>
                                            @else
                                                {{ $value }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    @endif
</x-app-layout>

<x-app-layout title="Riwayat Versi">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Riwayat Versi</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-400 mt-1">History perubahan aplikasi Kendaraan KAS</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('manual.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-bgray-100 px-4 py-2 text-sm font-medium text-bgray-700 hover:bg-bgray-200 dark:bg-darkblack-500 dark:text-white dark:hover:bg-darkblack-400">
                    <i class="fa fa-arrow-left"></i>
                    Kembali ke Panduan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-4">
        <!-- Sidebar: Version List -->
        <div class="lg:col-span-1">
            <div class="sticky top-24">
                <!-- Current Version Badge -->
                <div class="rounded-lg bg-accent-50 dark:bg-accent-300/10 p-4 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-accent-300 text-white">
                            <i class="fa fa-check"></i>
                        </div>
                        <div>
                            <p class="text-xs text-accent-400 dark:text-accent-300 font-medium">Versi Saat Ini</p>
                            <p class="text-lg font-bold text-bgray-900 dark:text-white">v{{ $currentVersion }}</p>
                        </div>
                    </div>
                </div>

                <!-- Version List -->
                <div class="rounded-lg bg-white dark:bg-darkblack-600 shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b border-bgray-200 dark:border-darkblack-400">
                        <h3 class="font-semibold text-bgray-900 dark:text-white text-sm">Semua Versi</h3>
                    </div>
                    <nav class="max-h-[400px] overflow-y-auto">
                        @foreach($versions as $version)
                        <a href="#v{{ $version['version'] }}"
                           class="flex items-center justify-between px-4 py-3 text-sm hover:bg-bgray-50 dark:hover:bg-darkblack-500 border-b border-bgray-100 dark:border-darkblack-400 last:border-0 transition-colors {{ $version['version'] === $currentVersion ? 'bg-accent-50 dark:bg-accent-300/10' : '' }}">
                            <span class="font-medium {{ $version['version'] === $currentVersion ? 'text-accent-400' : 'text-bgray-700 dark:text-bgray-300' }}">
                                v{{ $version['version'] }}
                            </span>
                            <span class="text-xs text-bgray-500 dark:text-bgray-400">
                                {{ \Carbon\Carbon::parse($version['date'])->format('d M Y') }}
                            </span>
                        </a>
                        @endforeach
                    </nav>
                </div>

                <!-- Quick Stats -->
                <div class="mt-4 rounded-lg bg-white dark:bg-darkblack-600 shadow-sm p-4">
                    <h3 class="font-semibold text-bgray-900 dark:text-white text-sm mb-3">Statistik</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-bgray-500 dark:text-bgray-400">Total Versi</span>
                            <span class="font-medium text-bgray-900 dark:text-white">{{ count($versions) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-bgray-500 dark:text-bgray-400">Versi Pertama</span>
                            <span class="font-medium text-bgray-900 dark:text-white">v{{ $versions[count($versions)-1]['version'] ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-bgray-500 dark:text-bgray-400">Versi Terbaru</span>
                            <span class="font-medium text-bgray-900 dark:text-white">v{{ $versions[0]['version'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Changelog -->
        <div class="lg:col-span-3">
            <div class="rounded-lg bg-white dark:bg-darkblack-600 shadow-sm">
                <div class="px-6 py-4 border-b border-bgray-200 dark:border-darkblack-400">
                    <h3 class="font-semibold text-bgray-900 dark:text-white">Changelog</h3>
                    <p class="text-sm text-bgray-500 dark:text-bgray-400 mt-1">Dokumentasi lengkap perubahan setiap versi</p>
                </div>

                <div class="p-6">
                    <!-- Parsed Changelog Content -->
                    <div class="changelog-content prose prose-sm dark:prose-invert max-w-none">
                        @php
                            // Parse changelog into sections
                            $sections = preg_split('/(?=## \[\d+\.\d+\.\d+\])/', $changelog);
                        @endphp

                        @foreach($sections as $section)
                            @if(preg_match('/## \[(\d+\.\d+\.\d+)\] - (\d{4}-\d{2}-\d{2})/', $section, $match))
                                @php
                                    $ver = $match[1];
                                    $date = $match[2];
                                    $content = preg_replace('/## \[\d+\.\d+\.\d+\] - \d{4}-\d{2}-\d{2}/', '', $section);
                                    $content = trim($content);
                                @endphp

                                <div id="v{{ $ver }}" class="version-block mb-8 pb-8 border-b border-bgray-200 dark:border-darkblack-400 last:border-0 scroll-mt-24">
                                    <!-- Version Header -->
                                    <div class="flex items-center gap-3 mb-4">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $ver === $currentVersion ? 'bg-accent-300 text-white' : 'bg-bgray-100 dark:bg-darkblack-500 text-bgray-700 dark:text-bgray-300' }}">
                                            v{{ $ver }}
                                        </span>
                                        <span class="text-sm text-bgray-500 dark:text-bgray-400">
                                            {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                                        </span>
                                        @if($ver === $currentVersion)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-accent-50 dark:bg-accent-300/10 px-2 py-0.5 text-xs font-medium text-accent-400">
                                                <i class="fa fa-check text-[10px]"></i> Current
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Version Content -->
                                    <div class="pl-4 border-l-2 border-bgray-200 dark:border-darkblack-400">
                                        @php
                                            // Parse sections (Added, Changed, Fixed)
                                            preg_match_all('/### (Added|Changed|Fixed|Removed)\n([\s\S]*?)(?=###|$|\*\*Commits)/', $content, $changes, PREG_SET_ORDER);
                                        @endphp

                                        @foreach($changes as $change)
                                            @php
                                                $type = $change[1];
                                                $items = $change[2];
                                                $icon = match($type) {
                                                    'Added' => 'fa-plus-circle text-accent-400',
                                                    'Changed' => 'fa-edit text-blue-500',
                                                    'Fixed' => 'fa-bug text-orange-500',
                                                    'Removed' => 'fa-minus-circle text-red-500',
                                                    default => 'fa-circle text-bgray-400'
                                                };
                                                $label = match($type) {
                                                    'Added' => 'Ditambahkan',
                                                    'Changed' => 'Diubah',
                                                    'Fixed' => 'Diperbaiki',
                                                    'Removed' => 'Dihapus',
                                                    default => $type
                                                };
                                            @endphp

                                            <div class="mb-4">
                                                <h4 class="flex items-center gap-2 text-sm font-semibold text-bgray-700 dark:text-bgray-300 mb-2">
                                                    <i class="fa {{ $icon }}"></i>
                                                    {{ $label }}
                                                </h4>
                                                <ul class="space-y-1 text-sm text-bgray-600 dark:text-bgray-400">
                                                    @foreach(explode("\n", trim($items)) as $item)
                                                        @if(str_starts_with(trim($item), '-'))
                                                            <li class="flex items-start gap-2">
                                                                <span class="text-bgray-400 mt-1">•</span>
                                                                <span>{{ trim(substr(trim($item), 1)) }}</span>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach

                                        @php
                                            // Extract commits
                                            preg_match('/\*\*Commits:\*\* `([^`]+)`/', $content, $commits);
                                        @endphp
                                        @if(!empty($commits[1]))
                                            <div class="mt-3 pt-3 border-t border-bgray-100 dark:border-darkblack-500">
                                                <span class="text-xs text-bgray-500 dark:text-bgray-400">
                                                    <i class="fa fa-code-branch mr-1"></i>
                                                    Commits: <code class="bg-bgray-100 dark:bg-darkblack-500 px-1 rounded">{{ $commits[1] }}</code>
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .changelog-content code {
            background-color: rgb(var(--bgray-100));
            padding: 0.125rem 0.25rem;
            border-radius: 0.25rem;
            font-size: 0.875em;
        }
        .dark .changelog-content code {
            background-color: rgb(var(--darkblack-500));
        }
    </style>
    @endpush
</x-app-layout>

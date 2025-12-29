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
                    <span class="hidden sm:inline">Kembali ke Panduan</span>
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Floating Version Button for Mobile --}}
    <button type="button" id="floating-version-btn" class="lg:hidden fixed bottom-6 right-6 z-50 flex items-center gap-2 rounded-full bg-accent-300 px-4 py-3 text-white shadow-lg hover:bg-accent-400 transition-all">
        <i class="fa fa-code-branch"></i>
        <span class="text-sm font-medium">v{{ $currentVersion }}</span>
    </button>

    {{-- Mobile Version Modal --}}
    <div id="mobile-version-modal" class="lg:hidden fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" id="mobile-version-backdrop"></div>
        <div class="absolute bottom-0 left-0 right-0 max-h-[70vh] rounded-t-2xl bg-white dark:bg-darkblack-600 shadow-xl">
            <div class="flex items-center justify-between border-b border-bgray-200 dark:border-darkblack-400 px-4 py-3">
                <div>
                    <h3 class="font-semibold text-bgray-900 dark:text-white">Semua Versi</h3>
                    <p class="text-xs text-bgray-500 dark:text-bgray-400">Versi saat ini: v{{ $currentVersion }}</p>
                </div>
                <button type="button" id="close-version-modal" class="flex h-8 w-8 items-center justify-center rounded-lg hover:bg-bgray-100 dark:hover:bg-darkblack-500">
                    <i class="fa fa-times text-bgray-500"></i>
                </button>
            </div>
            <nav class="overflow-y-auto p-4" style="max-height: calc(70vh - 60px);">
                @foreach($versions as $version)
                <a href="#v{{ $version['version'] }}" class="mobile-version-link flex items-center justify-between rounded-lg px-4 py-3 text-sm hover:bg-accent-50 dark:hover:bg-darkblack-500 {{ $version['version'] === $currentVersion ? 'bg-accent-50 dark:bg-accent-300/10' : '' }}">
                    <span class="font-medium {{ $version['version'] === $currentVersion ? 'text-accent-400' : 'text-bgray-700 dark:text-bgray-300' }}">
                        v{{ $version['version'] }}
                        @if($version['version'] === $currentVersion)
                        <span class="ml-2 text-xs bg-accent-300 text-white px-1.5 py-0.5 rounded">Current</span>
                        @endif
                    </span>
                    <span class="text-xs text-bgray-500 dark:text-bgray-400">
                        {{ \Carbon\Carbon::parse($version['date'])->format('d M Y') }}
                    </span>
                </a>
                @endforeach
            </nav>
        </div>
    </div>

    {{-- Fixed Desktop TOC --}}
    <div class="hidden lg:block fixed right-6 top-24 w-72 z-40" id="desktop-version-toc">
        <div class="rounded-lg bg-white shadow-lg dark:bg-darkblack-600 overflow-hidden border border-bgray-100 dark:border-darkblack-400">
            {{-- Current Version Badge --}}
            <div class="bg-accent-50 dark:bg-accent-300/10 px-4 py-3 border-b border-bgray-100 dark:border-darkblack-400">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-accent-300 text-white text-sm">
                        <i class="fa fa-check"></i>
                    </div>
                    <div>
                        <p class="text-xs text-accent-400 dark:text-accent-300 font-medium">Versi Saat Ini</p>
                        <p class="text-base font-bold text-bgray-900 dark:text-white">v{{ $currentVersion }}</p>
                    </div>
                </div>
            </div>

            <div class="p-3">
                <h3 class="mb-2 text-xs font-semibold text-bgray-500 dark:text-bgray-400 uppercase tracking-wider px-2">Semua Versi</h3>
                <nav class="space-y-0.5 max-h-[calc(100vh-320px)] overflow-y-auto pr-1" id="version-nav">
                    @foreach($versions as $version)
                    <a href="#v{{ $version['version'] }}"
                       class="version-nav-link group flex items-center justify-between rounded-lg px-3 py-2 text-sm transition-colors {{ $version['version'] === $currentVersion ? 'bg-accent-50 dark:bg-accent-300/10 text-accent-400' : 'text-bgray-700 hover:bg-bgray-50 dark:text-bgray-300 dark:hover:bg-darkblack-500' }}"
                       data-version="{{ $version['version'] }}">
                        <span class="font-medium">v{{ $version['version'] }}</span>
                        <span class="text-xs text-bgray-400 dark:text-bgray-500">{{ \Carbon\Carbon::parse($version['date'])->format('d M') }}</span>
                    </a>
                    @endforeach
                </nav>
            </div>

            {{-- Quick Stats --}}
            <div class="border-t border-bgray-100 dark:border-darkblack-400 px-4 py-3">
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div>
                        <p class="text-lg font-bold text-bgray-900 dark:text-white">{{ count($versions) }}</p>
                        <p class="text-xs text-bgray-500 dark:text-bgray-400">Total</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-bgray-900 dark:text-white">v{{ $versions[count($versions)-1]['version'] ?? '-' }}</p>
                        <p class="text-xs text-bgray-500 dark:text-bgray-400">Pertama</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-accent-400">v{{ $versions[0]['version'] ?? '-' }}</p>
                        <p class="text-xs text-bgray-500 dark:text-bgray-400">Terbaru</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content with right padding for fixed TOC --}}
    <div class="lg:pr-80">
        <div class="rounded-lg bg-white dark:bg-darkblack-600 shadow-sm">
            <div class="px-6 py-4 border-b border-bgray-200 dark:border-darkblack-400">
                <h3 class="text-lg font-semibold text-bgray-900 dark:text-white">Changelog</h3>
                <p class="text-base text-bgray-500 dark:text-bgray-400 mt-1">Dokumentasi lengkap perubahan setiap versi</p>
            </div>

            <div class="p-6">
                <!-- Parsed Changelog Content -->
                <div class="changelog-content">
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
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <span class="inline-flex items-center rounded-full px-4 py-1.5 text-base font-semibold {{ $ver === $currentVersion ? 'bg-accent-300 text-white' : 'bg-bgray-100 dark:bg-darkblack-500 text-bgray-700 dark:text-bgray-300' }}">
                                        v{{ $ver }}
                                    </span>
                                    <span class="text-base text-bgray-500 dark:text-bgray-400">
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
                                            <h4 class="flex items-center gap-2 text-base font-semibold text-bgray-700 dark:text-bgray-300 mb-2">
                                                <i class="fa {{ $icon }}"></i>
                                                {{ $label }}
                                            </h4>
                                            <ul class="space-y-1.5 text-base text-bgray-600 dark:text-bgray-400">
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
        .version-nav-link.active {
            background-color: var(--accent-50);
            color: var(--accent-400);
        }
        .dark .version-nav-link.active {
            background-color: rgba(var(--accent-300), 0.1);
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile modal toggle
            const floatingBtn = document.getElementById('floating-version-btn');
            const modal = document.getElementById('mobile-version-modal');
            const backdrop = document.getElementById('mobile-version-backdrop');
            const closeBtn = document.getElementById('close-version-modal');

            if (floatingBtn && modal) {
                floatingBtn.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                });

                [backdrop, closeBtn].forEach(el => {
                    if (el) {
                        el.addEventListener('click', () => {
                            modal.classList.add('hidden');
                        });
                    }
                });

                // Close modal when clicking a version link
                document.querySelectorAll('.mobile-version-link').forEach(link => {
                    link.addEventListener('click', () => {
                        modal.classList.add('hidden');
                    });
                });
            }

            // Highlight active version on scroll (desktop)
            const versionBlocks = document.querySelectorAll('.version-block');
            const navLinks = document.querySelectorAll('.version-nav-link');

            if (versionBlocks.length > 0 && navLinks.length > 0) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const version = entry.target.id.replace('v', '');
                            navLinks.forEach(link => {
                                if (link.dataset.version === version) {
                                    link.classList.add('active');
                                } else {
                                    link.classList.remove('active');
                                }
                            });

                            // Update floating button text on mobile
                            if (floatingBtn) {
                                floatingBtn.querySelector('span').textContent = 'v' + version;
                            }
                        }
                    });
                }, {
                    rootMargin: '-20% 0px -70% 0px'
                });

                versionBlocks.forEach(block => observer.observe(block));
            }
        });
    </script>
    @endpush
</x-app-layout>

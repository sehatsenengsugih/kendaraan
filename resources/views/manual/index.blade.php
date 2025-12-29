<x-app-layout title="Panduan Pengguna">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Panduan Pengguna</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-400 mt-1">Manual lengkap penggunaan aplikasi Kendaraan KAS</p>
            </div>
            <div class="flex items-center gap-3">
                @can('manage-users')
                <a href="{{ route('manual.admin.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-bgray-100 px-4 py-2 text-sm font-medium text-bgray-700 hover:bg-bgray-200 dark:bg-darkblack-500 dark:text-white dark:hover:bg-darkblack-400">
                    <i class="fa fa-cog"></i>
                    Kelola
                </a>
                @endcan
                <a href="#" onclick="window.print(); return false;" class="inline-flex items-center gap-2 rounded-lg bg-bgray-200 px-4 py-2 text-sm font-medium text-bgray-700 hover:bg-bgray-300 dark:bg-darkblack-500 dark:text-white dark:hover:bg-darkblack-400">
                    <i class="fa fa-print"></i>
                    <span class="hidden sm:inline">Cetak</span>
                </a>
            </div>
        </div>
    </x-slot>

    @if($sections->isEmpty())
        {{-- Empty State --}}
        <div class="rounded-lg bg-white p-12 shadow-sm dark:bg-darkblack-600 text-center">
            <svg class="mx-auto h-16 w-16 text-bgray-300 dark:text-bgray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-2">Panduan Belum Tersedia</h3>
            <p class="text-bgray-500 dark:text-bgray-400 mb-6">Belum ada panduan yang ditambahkan ke sistem.</p>
            @can('manage-users')
            <a href="{{ route('manual.admin.create') }}" class="inline-flex items-center rounded-lg bg-accent-300 px-6 py-3 text-sm font-medium text-white hover:bg-accent-400 transition-colors">
                <i class="fa fa-plus mr-2"></i> Buat Panduan Pertama
            </a>
            @endcan
        </div>
    @else
        {{-- Floating TOC Button for Mobile --}}
        <button type="button" id="floating-toc-btn" class="lg:hidden fixed bottom-6 right-6 z-50 flex items-center gap-2 rounded-full bg-accent-300 px-4 py-3 text-white shadow-lg hover:bg-accent-400 transition-all">
            <i class="fa fa-list"></i>
            <span class="text-sm font-medium" id="current-section-label">Daftar Isi</span>
        </button>

        {{-- Mobile TOC Modal --}}
        <div id="mobile-toc-modal" class="lg:hidden fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-black/50" id="mobile-toc-backdrop"></div>
            <div class="absolute bottom-0 left-0 right-0 max-h-[70vh] rounded-t-2xl bg-white dark:bg-darkblack-600 shadow-xl transform transition-transform">
                <div class="flex items-center justify-between border-b border-bgray-200 dark:border-darkblack-400 px-4 py-3">
                    <h3 class="font-semibold text-bgray-900 dark:text-white">Daftar Isi</h3>
                    <button type="button" id="close-toc-modal" class="flex h-8 w-8 items-center justify-center rounded-lg hover:bg-bgray-100 dark:hover:bg-darkblack-500">
                        <i class="fa fa-times text-bgray-500"></i>
                    </button>
                </div>
                <nav class="overflow-y-auto p-4" style="max-height: calc(70vh - 60px);">
                    @foreach($sections as $section)
                    <a href="#{{ $section->slug }}" class="mobile-nav-link flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-bgray-700 hover:bg-accent-50 hover:text-accent-400 dark:text-bgray-300 dark:hover:bg-darkblack-500" data-section="{{ $section->slug }}">
                        @if($section->icon)
                        <span class="w-5 h-5 flex items-center justify-center text-accent-400">{!! $section->icon !!}</span>
                        @else
                        <span class="w-5 h-5 flex items-center justify-center rounded-full bg-accent-100 text-accent-400 text-xs">{{ $loop->iteration }}</span>
                        @endif
                        <span>{{ $section->title }}</span>
                    </a>
                    @if($section->children->count() > 0)
                        @foreach($section->children as $child)
                        <a href="#{{ $child->slug }}" class="mobile-nav-link flex items-center gap-3 rounded-lg px-4 py-3 pl-12 text-sm text-bgray-600 hover:bg-accent-50 hover:text-accent-400 dark:text-bgray-400 dark:hover:bg-darkblack-500" data-section="{{ $child->slug }}">
                            <span>{{ $child->title }}</span>
                        </a>
                        @endforeach
                    @endif
                    @endforeach

                    <!-- Riwayat Versi Link -->
                    <div class="mt-4 pt-4 border-t border-bgray-200 dark:border-darkblack-400">
                        <a href="{{ route('manual.version-history') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-bgray-700 hover:bg-accent-50 hover:text-accent-400 dark:text-bgray-300 dark:hover:bg-darkblack-500">
                            <span class="w-5 h-5 flex items-center justify-center text-accent-400"><i class="fa fa-history"></i></span>
                            <span>Riwayat Versi</span>
                            <span class="ml-auto text-[10px] bg-accent-100 text-accent-400 px-1.5 py-0.5 rounded dark:bg-accent-300/20">v{{ trim(file_get_contents(base_path('VERSION'))) }}</span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>

        {{-- Fixed Desktop TOC --}}
        <div class="hidden lg:block fixed right-6 top-24 w-64 z-40" id="desktop-toc">
            <div class="rounded-lg bg-white shadow-lg dark:bg-darkblack-600 overflow-hidden border border-bgray-100 dark:border-darkblack-400">
                {{-- Progress Indicator --}}
                <div class="h-1 bg-bgray-100 dark:bg-darkblack-500">
                    <div id="reading-progress" class="h-full bg-accent-300 transition-all duration-300" style="width: 0%"></div>
                </div>

                <div class="p-4">
                    <h3 class="mb-3 text-xs font-semibold text-bgray-500 dark:text-bgray-400 uppercase tracking-wider">Daftar Isi</h3>
                    <nav class="space-y-0.5 max-h-[calc(100vh-220px)] overflow-y-auto pr-2 -mr-2" id="toc-nav">
                        @foreach($sections as $section)
                        <div class="nav-group">
                            <a href="#{{ $section->slug }}" class="manual-nav-link group flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-bgray-700 hover:bg-accent-50 hover:text-accent-400 dark:text-bgray-300 dark:hover:bg-darkblack-500 transition-colors" data-section="{{ $section->slug }}">
                                @if($section->icon)
                                <span class="w-4 h-4 flex items-center justify-center text-bgray-400 group-hover:text-accent-400 transition-colors flex-shrink-0">{!! $section->icon !!}</span>
                                @else
                                <span class="w-4 h-4 flex items-center justify-center rounded-full bg-bgray-100 group-hover:bg-accent-100 text-bgray-500 group-hover:text-accent-400 text-xs transition-colors flex-shrink-0">{{ $loop->iteration }}</span>
                                @endif
                                <span class="truncate">{{ $section->title }}</span>
                            </a>
                        </div>
                        @endforeach
                    </nav>
                </div>

                {{-- Quick Actions --}}
                <div class="border-t border-bgray-100 dark:border-darkblack-400 px-4 py-3 space-y-2">
                    <a href="{{ route('manual.version-history') }}" class="flex items-center gap-2 text-xs text-bgray-500 hover:text-accent-400 dark:text-bgray-400 transition-colors">
                        <i class="fa fa-history"></i>
                        <span>Riwayat Versi</span>
                        <span class="ml-auto text-[10px] bg-accent-100 text-accent-400 px-1.5 py-0.5 rounded dark:bg-accent-300/20">v{{ trim(file_get_contents(base_path('VERSION'))) }}</span>
                    </a>
                    <a href="#" onclick="document.getElementById('manual-content').scrollTo({top: 0, behavior: 'smooth'}); return false;" class="flex items-center gap-2 text-xs text-bgray-500 hover:text-accent-400 dark:text-bgray-400 transition-colors">
                        <i class="fa fa-arrow-up"></i>
                        <span>Kembali ke atas</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:pr-72" id="manual-content">

            {{-- Main Content --}}
            <div class="flex-1 space-y-6">
                @foreach($sections as $section)
                <section id="{{ $section->slug }}" class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600 scroll-mt-24">
                    <div class="flex items-start justify-between gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-accent-50 text-accent-400">
                                @if($section->icon)
                                {!! $section->icon !!}
                                @else
                                <span class="text-lg font-bold">{{ $loop->iteration }}</span>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-bgray-900 dark:text-white">{{ $section->title }}</h2>
                            </div>
                        </div>
                        @can('manage-users')
                        <a href="{{ route('manual.admin.edit', $section) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-bgray-400 hover:text-warning-400 hover:bg-warning-50 dark:hover:bg-darkblack-500 transition-colors" title="Edit bagian ini">
                            <i class="fa fa-edit"></i>
                        </a>
                        @endcan
                    </div>

                    <div class="prose prose-base max-w-none dark:prose-invert prose-headings:text-bgray-900 dark:prose-headings:text-white prose-p:text-bgray-700 dark:prose-p:text-bgray-300 prose-a:text-accent-400 prose-strong:text-bgray-900 dark:prose-strong:text-white">
                        {!! $section->content !!}
                    </div>

                    {{-- Sub-sections --}}
                    @if($section->children->count() > 0)
                    <div class="mt-8 space-y-6 border-t border-bgray-100 dark:border-darkblack-400 pt-6">
                        @foreach($section->children as $child)
                        <div id="{{ $child->slug }}" class="scroll-mt-24">
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <h3 class="text-lg font-semibold text-bgray-900 dark:text-white flex items-center gap-2">
                                    <span class="w-6 h-6 flex items-center justify-center rounded-full bg-accent-100 text-accent-400 text-xs">{{ $loop->iteration }}</span>
                                    {{ $child->title }}
                                </h3>
                                @can('manage-users')
                                <a href="{{ route('manual.admin.edit', $child) }}" class="inline-flex items-center justify-center h-6 w-6 rounded text-bgray-400 hover:text-warning-400 transition-colors" title="Edit sub-bagian">
                                    <i class="fa fa-edit text-sm"></i>
                                </a>
                                @endcan
                            </div>
                            <div class="prose prose-base max-w-none dark:prose-invert prose-headings:text-bgray-900 dark:prose-headings:text-white prose-p:text-bgray-700 dark:prose-p:text-bgray-300 prose-a:text-accent-400 ml-8">
                                {!! $child->content !!}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </section>
                @endforeach

                {{-- Navigation Footer --}}
                <div class="flex items-center justify-between py-6">
                    <div>
                        @if($sections->first())
                        <a href="#{{ $sections->first()->slug }}" class="inline-flex items-center gap-2 text-sm text-bgray-500 hover:text-accent-400 dark:text-bgray-400 transition-colors">
                            <i class="fa fa-arrow-up"></i>
                            Kembali ke atas
                        </a>
                        @endif
                    </div>
                    @can('manage-users')
                    <a href="{{ route('manual.admin.index') }}" class="inline-flex items-center gap-2 text-sm text-bgray-500 hover:text-accent-400 dark:text-bgray-400 transition-colors">
                        <i class="fa fa-cog"></i>
                        Kelola Panduan
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.manual-nav-link');
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
            const progressBar = document.getElementById('reading-progress');
            const floatingBtn = document.getElementById('floating-toc-btn');
            const tocModal = document.getElementById('mobile-toc-modal');
            const tocBackdrop = document.getElementById('mobile-toc-backdrop');
            const closeTocBtn = document.getElementById('close-toc-modal');
            const currentSectionLabel = document.getElementById('current-section-label');

            // Section titles map for floating button
            const sectionTitles = {
                @foreach($sections as $section)
                '{{ $section->slug }}': '{{ $section->title }}',
                @if($section->children->count() > 0)
                    @foreach($section->children as $child)
                    '{{ $child->slug }}': '{{ $child->title }}',
                    @endforeach
                @endif
                @endforeach
            };

            // Mobile TOC Modal
            if (floatingBtn && tocModal) {
                floatingBtn.addEventListener('click', function() {
                    tocModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });

                const closeModal = () => {
                    tocModal.classList.add('hidden');
                    document.body.style.overflow = '';
                };

                closeTocBtn.addEventListener('click', closeModal);
                tocBackdrop.addEventListener('click', closeModal);

                // Close modal when clicking a link
                mobileNavLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        closeModal();
                    });
                });
            }

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href').slice(1);
                    const target = document.getElementById(targetId);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({ behavior: 'smooth' });
                        history.pushState(null, null, '#' + targetId);
                    }
                });
            });

            // Highlight active section and update progress
            function updateNav() {
                let currentSection = '';
                let currentSectionTitle = 'Daftar Isi';
                const scrollTop = window.scrollY;
                const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                const progress = (scrollTop / docHeight) * 100;

                // Update progress bar
                if (progressBar) {
                    progressBar.style.width = Math.min(progress, 100) + '%';
                }

                // Find current section
                sections.forEach(section => {
                    const sectionTop = section.offsetTop - 150;
                    const sectionBottom = sectionTop + section.offsetHeight;
                    if (scrollTop >= sectionTop && scrollTop < sectionBottom) {
                        currentSection = section.getAttribute('id');
                        currentSectionTitle = sectionTitles[currentSection] || 'Daftar Isi';
                    }
                });

                // Update floating button label
                if (currentSectionLabel) {
                    // Truncate long titles
                    const maxLen = 15;
                    const displayTitle = currentSectionTitle.length > maxLen
                        ? currentSectionTitle.substring(0, maxLen) + '...'
                        : currentSectionTitle;
                    currentSectionLabel.textContent = displayTitle;
                }

                // Update nav links
                navLinks.forEach(link => {
                    const linkSection = link.getAttribute('data-section');
                    link.classList.remove('bg-accent-50', 'text-accent-400', 'font-medium');

                    if (linkSection === currentSection) {
                        link.classList.add('bg-accent-50', 'text-accent-400', 'font-medium');
                    }
                });

                // Update mobile nav links
                mobileNavLinks.forEach(link => {
                    const linkSection = link.getAttribute('data-section');
                    link.classList.remove('bg-accent-50', 'text-accent-400', 'font-medium');

                    if (linkSection === currentSection) {
                        link.classList.add('bg-accent-50', 'text-accent-400', 'font-medium');
                    }
                });
            }

            // Throttled scroll handler
            let ticking = false;
            window.addEventListener('scroll', function() {
                if (!ticking) {
                    window.requestAnimationFrame(function() {
                        updateNav();
                        ticking = false;
                    });
                    ticking = true;
                }
            });

            // Initial update
            updateNav();

            // Handle hash on load
            if (window.location.hash) {
                const target = document.querySelector(window.location.hash);
                if (target) {
                    setTimeout(() => {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }, 100);
                }
            }

        });
    </script>
    @endpush

    <style>
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Custom scrollbar for TOC nav */
        #toc-nav::-webkit-scrollbar {
            width: 4px;
        }
        #toc-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        #toc-nav::-webkit-scrollbar-thumb {
            background: var(--accent-200);
            border-radius: 2px;
        }

        /* Mobile TOC modal animation */
        #mobile-toc-modal > div:last-child {
            animation: slideUp 0.3s ease-out;
        }
        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }
            to {
                transform: translateY(0);
            }
        }

        /* Prose customization */
        .prose h3 {
            @apply text-lg font-semibold mt-6 mb-3;
        }
        .prose h4 {
            @apply text-base font-medium mt-4 mb-2;
        }
        .prose ul, .prose ol {
            @apply my-3;
        }
        .prose li {
            @apply my-1;
        }
        .prose img {
            @apply rounded-lg shadow-sm;
        }
        .prose table {
            @apply text-sm;
        }
        .prose table th {
            @apply bg-bgray-50 dark:bg-darkblack-500;
        }

        /* Print styles */
        @media print {
            .sidebar-wrapper,
            .header-wrapper,
            #floating-toc-btn,
            #mobile-toc-modal,
            [onclick*="print"],
            [href*="manual.admin"],
            #desktop-toc {
                display: none !important;
            }
            .body-wrapper {
                margin-left: 0 !important;
            }
            section {
                break-inside: avoid;
                page-break-inside: avoid;
                box-shadow: none !important;
                border: 1px solid #e5e7eb;
            }
            .lg\\:w-72 {
                display: none !important;
            }
        }
    </style>
</x-app-layout>

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        {{-- Mobile: Info + Navigation --}}
        <div class="flex flex-col items-center gap-3 sm:hidden">
            {{-- Info --}}
            <p class="text-sm text-bgray-600 dark:text-bgray-300">
                <span class="font-medium">{{ $paginator->firstItem() ?? 0 }}</span>-<span class="font-medium">{{ $paginator->lastItem() ?? 0 }}</span>
                dari <span class="font-medium">{{ $paginator->total() }}</span>
            </p>
            {{-- Navigation --}}
            <div class="flex items-center gap-2">
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center rounded-lg bg-bgray-100 px-3 py-2 text-sm text-bgray-400 dark:bg-darkblack-500">
                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Sebelumnya
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center rounded-lg bg-white px-3 py-2 text-sm font-medium text-bgray-700 shadow-sm ring-1 ring-bgray-200 hover:bg-bgray-50 dark:bg-darkblack-500 dark:text-white dark:ring-darkblack-400">
                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Sebelumnya
                    </a>
                @endif

                <span class="text-sm font-medium text-bgray-700 dark:text-white">{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center rounded-lg bg-white px-3 py-2 text-sm font-medium text-bgray-700 shadow-sm ring-1 ring-bgray-200 hover:bg-bgray-50 dark:bg-darkblack-500 dark:text-white dark:ring-darkblack-400">
                        Berikutnya
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <span class="inline-flex items-center rounded-lg bg-bgray-100 px-3 py-2 text-sm text-bgray-400 dark:bg-darkblack-500">
                        Berikutnya
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                @endif
            </div>
        </div>

        {{-- Desktop --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-bgray-600 dark:text-bgray-300">
                    Menampilkan
                    @if ($paginator->firstItem())
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        sampai
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    dari
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-lg shadow-sm">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center rounded-l-lg border border-bgray-200 bg-white px-3 py-2 text-sm text-bgray-400 dark:border-darkblack-400 dark:bg-darkblack-500">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center rounded-l-lg border border-bgray-200 bg-white px-3 py-2 text-sm text-bgray-700 transition hover:bg-bgray-50 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white dark:hover:bg-darkblack-400" title="Halaman Sebelumnya">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="relative -ml-px inline-flex items-center border border-bgray-200 bg-white px-4 py-2 text-sm text-bgray-500 dark:border-darkblack-400 dark:bg-darkblack-500">{{ $element }}</span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="relative -ml-px inline-flex items-center border border-accent-300 bg-accent-50 px-4 py-2 text-sm font-medium text-accent-400 dark:border-accent-400 dark:bg-accent-400/20">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="relative -ml-px inline-flex items-center border border-bgray-200 bg-white px-4 py-2 text-sm text-bgray-700 transition hover:bg-bgray-50 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white dark:hover:bg-darkblack-400" title="Halaman {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative -ml-px inline-flex items-center rounded-r-lg border border-bgray-200 bg-white px-3 py-2 text-sm text-bgray-700 transition hover:bg-bgray-50 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white dark:hover:bg-darkblack-400" title="Halaman Berikutnya">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span class="relative -ml-px inline-flex items-center rounded-r-lg border border-bgray-200 bg-white px-3 py-2 text-sm text-bgray-400 dark:border-darkblack-400 dark:bg-darkblack-500">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif

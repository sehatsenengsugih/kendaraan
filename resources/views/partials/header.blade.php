<header class="header-wrapper fixed z-20 hidden w-full items-center justify-between bg-white dark:bg-darkblack-600 px-6 py-5 shadow-sm xl:flex">
    <div class="flex items-center space-x-4">
        <!-- Drawer Toggle Button (Desktop) -->
        <button type="button" class="drawer-btn hidden xl:flex items-center justify-center h-10 w-10 rounded-lg hover:bg-bgray-100 dark:hover:bg-darkblack-500 transition-colors" title="Toggle Sidebar (Ctrl+B)">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-bgray-600 dark:text-bgray-300">
                <path d="M3 12H21M3 6H21M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <!-- Global Search Bar -->
        <div class="relative hidden lg:block" x-data="globalSearch()" @keydown.window.ctrl.k.prevent="focusSearch()">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 z-10">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="9.78639" cy="9.78614" r="8.23951" stroke="#A0AEC0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15.5176 15.9448L18.7479 19.1668" stroke="#A0AEC0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <input type="text"
                   x-ref="searchInput"
                   x-model="query"
                   @input.debounce.300ms="search()"
                   @focus="showDropdown = query.length >= 2"
                   @keydown.escape="showDropdown = false"
                   @keydown.arrow-down.prevent="navigateDown()"
                   @keydown.arrow-up.prevent="navigateUp()"
                   @keydown.enter.prevent="selectResult()"
                   placeholder="Cari kendaraan, plat nomor... (Ctrl+K)"
                   class="w-[350px] rounded-lg border border-bgray-200 py-3 pl-12 pr-4 text-sm text-bgray-800 placeholder:text-bgray-500 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white dark:placeholder:text-bgray-300">

            <!-- Loading Indicator -->
            <div x-show="loading" class="absolute right-4 top-1/2 -translate-y-1/2">
                <svg class="animate-spin h-4 w-4 text-bgray-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </div>

            <!-- Search Results Dropdown -->
            <div x-show="showDropdown"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-1"
                 @click.away="showDropdown = false"
                 class="absolute left-0 right-0 top-full mt-2 rounded-lg bg-white dark:bg-darkblack-600 shadow-xl border border-bgray-200 dark:border-darkblack-400 overflow-hidden z-50 max-h-[400px] overflow-y-auto">

                <!-- No Results -->
                <div x-show="!loading && results.length === 0 && query.length >= 2" class="px-4 py-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-bgray-300 dark:text-bgray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <p class="text-sm text-bgray-500 dark:text-bgray-400">Tidak ada hasil untuk "<span x-text="query"></span>"</p>
                </div>

                <!-- Results List -->
                <template x-for="(result, index) in results" :key="index">
                    <a :href="result.url"
                       :class="{ 'bg-bgray-50 dark:bg-darkblack-500': selectedIndex === index }"
                       @mouseenter="selectedIndex = index"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-bgray-50 dark:hover:bg-darkblack-500 border-b border-bgray-100 dark:border-darkblack-400 last:border-0 transition-colors">
                        <!-- Avatar/Icon -->
                        <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-bgray-100 dark:bg-darkblack-400 flex items-center justify-center overflow-hidden">
                            <template x-if="result.avatar">
                                <img :src="result.avatar" class="h-full w-full object-cover" :alt="result.title">
                            </template>
                            <template x-if="!result.avatar">
                                <i :class="'fa ' + result.icon" class="text-bgray-500 dark:text-bgray-300"></i>
                            </template>
                        </div>
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-bgray-900 dark:text-white truncate" x-text="result.title"></span>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-bgray-100 dark:bg-darkblack-400 text-bgray-600 dark:text-bgray-300" x-text="result.type_label"></span>
                            </div>
                            <p class="text-xs text-bgray-500 dark:text-bgray-400 truncate">
                                <span x-text="result.subtitle"></span>
                                <span x-show="result.description"> • </span>
                                <span x-text="result.description"></span>
                            </p>
                        </div>
                        <!-- Arrow -->
                        <svg class="w-4 h-4 text-bgray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </template>

                <!-- Footer -->
                <div x-show="results.length > 0" class="px-4 py-2 bg-bgray-50 dark:bg-darkblack-500 border-t border-bgray-200 dark:border-darkblack-400">
                    <p class="text-xs text-bgray-500 dark:text-bgray-400">
                        <span x-text="results.length"></span> hasil ditemukan •
                        <kbd class="px-1.5 py-0.5 bg-white dark:bg-darkblack-400 rounded text-[10px] border border-bgray-200 dark:border-darkblack-300">↑↓</kbd> navigasi
                        <kbd class="px-1.5 py-0.5 bg-white dark:bg-darkblack-400 rounded text-[10px] border border-bgray-200 dark:border-darkblack-300 ml-1">Enter</kbd> pilih
                        <kbd class="px-1.5 py-0.5 bg-white dark:bg-darkblack-400 rounded text-[10px] border border-bgray-200 dark:border-darkblack-300 ml-1">Esc</kbd> tutup
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center space-x-4">
        <!-- Theme Toggle -->
        <button type="button" id="theme-toggle" class="flex h-10 w-10 items-center justify-center rounded-full bg-bgray-100 hover:bg-bgray-200 dark:bg-darkblack-500 dark:hover:bg-darkblack-400 transition-colors" title="Toggle Dark/Light Mode">
            <!-- Sun Icon (shown in dark mode) -->
            <svg class="hidden dark:block w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
            </svg>
            <!-- Moon Icon (shown in light mode) -->
            <svg class="block dark:hidden w-5 h-5 text-bgray-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
        </button>

        <!-- Notifications -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" type="button" class="relative flex h-10 w-10 items-center justify-center rounded-full bg-bgray-100 hover:bg-bgray-200 dark:bg-darkblack-500 dark:hover:bg-darkblack-400 transition-colors">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 2C6.68629 2 4 4.68629 4 8V11.5858L3.29289 12.2929C3.00517 12.5806 2.92797 13.0124 3.09922 13.3833C3.27047 13.7542 3.65283 14 4 14H16C16.3472 14 16.7295 13.7542 16.9008 13.3833C17.072 13.0124 16.9948 12.5806 16.7071 12.2929L16 11.5858V8C16 4.68629 13.3137 2 10 2Z" fill="#A0AEC0"/>
                    <path d="M10 18C11.6569 18 13 16.6569 13 15H7C7 16.6569 8.34315 18 10 18Z" fill="#22C55E"/>
                </svg>
                <!-- Notification Badge -->
                @if($notificationCount > 0)
                <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-error-200 text-xs text-white">{{ $notificationCount > 9 ? '9+' : $notificationCount }}</span>
                @endif
            </button>

            <!-- Notification Dropdown -->
            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 rounded-lg bg-white shadow-lg dark:bg-darkblack-500 border border-bgray-200 dark:border-darkblack-400 overflow-hidden">
                <div class="px-4 py-3 border-b border-bgray-200 dark:border-darkblack-400 flex items-center justify-between">
                    <h4 class="font-semibold text-bgray-900 dark:text-white">Notifikasi</h4>
                    @if($notificationCount > 0)
                    <span class="text-xs bg-error-100 text-error-300 px-2 py-1 rounded-full">{{ $notificationCount }} baru</span>
                    @endif
                </div>

                <div class="max-h-80 overflow-y-auto">
                    @forelse($notifications as $notification)
                    <a href="{{ $notification['url'] }}" class="block px-4 py-3 hover:bg-bgray-50 dark:hover:bg-darkblack-400 border-b border-bgray-100 dark:border-darkblack-400 last:border-0">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-1">
                                @if($notification['color'] === 'error')
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-error-50">
                                    <i class="fa {{ $notification['icon'] }} text-error-300 text-sm"></i>
                                </span>
                                @elseif($notification['color'] === 'warning')
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-warning-50">
                                    <i class="fa {{ $notification['icon'] }} text-warning-300 text-sm"></i>
                                </span>
                                @else
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-success-50">
                                    <i class="fa {{ $notification['icon'] }} text-success-300 text-sm"></i>
                                </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-bgray-900 dark:text-white">{{ $notification['title'] }}</p>
                                <p class="text-sm text-bgray-600 dark:text-bgray-300 truncate">{{ $notification['message'] }}</p>
                                <p class="text-xs text-bgray-500 dark:text-bgray-400 mt-1">{{ $notification['detail'] }}</p>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="px-4 py-8 text-center">
                        <svg class="w-12 h-12 mx-auto text-bgray-300 dark:text-bgray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <p class="text-sm text-bgray-500 dark:text-bgray-400">Tidak ada notifikasi</p>
                    </div>
                    @endforelse
                </div>

                @if($notificationCount > 0)
                <div class="px-4 py-3 border-t border-bgray-200 dark:border-darkblack-400 text-center">
                    <a href="{{ route('pajak.index') }}" class="text-sm text-success-300 hover:text-success-400 font-medium">Lihat Semua Pajak</a>
                    <span class="text-bgray-300 mx-2">|</span>
                    <a href="{{ route('servis.index') }}" class="text-sm text-success-300 hover:text-success-400 font-medium">Lihat Semua Servis</a>
                </div>
                @endif
            </div>
        </div>

        <!-- User Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" type="button" class="flex items-center space-x-3">
                <div class="h-10 w-10 overflow-hidden rounded-full bg-success-100">
                    <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'User') . '&background=22C55E&color=fff' }}" alt="User" class="h-full w-full object-cover">
                </div>
                <div class="hidden text-left lg:block">
                    <h4 class="text-sm font-bold text-bgray-900 dark:text-white">{{ Auth::user()->name ?? 'User' }}</h4>
                    <p class="text-xs text-bgray-600 dark:text-bgray-300">
                        @if(Auth::check())
                            @if(Auth::user()->role === 'super_admin')
                                Super Admin
                            @elseif(Auth::user()->role === 'admin')
                                Admin
                            @else
                                User
                            @endif
                        @endif
                    </p>
                </div>
                <span class="hidden lg:block">
                    <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L6 6L11 1" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </button>

            <!-- User Dropdown Menu -->
            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 rounded-lg bg-white py-2 shadow-lg dark:bg-darkblack-500 border border-bgray-200 dark:border-darkblack-400">
                <div class="px-4 py-2 border-b border-bgray-200 dark:border-darkblack-400">
                    <p class="text-sm font-semibold text-bgray-900 dark:text-white">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-bgray-500 dark:text-bgray-300">{{ Auth::user()->email ?? '' }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-bgray-700 hover:bg-bgray-100 dark:text-white dark:hover:bg-darkblack-400">
                    <i class="fa fa-user mr-3 w-4 text-bgray-500"></i> Profil Saya
                </a>
                @can('manage-users')
                <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2 text-sm text-bgray-700 hover:bg-bgray-100 dark:text-white dark:hover:bg-darkblack-400">
                    <i class="fa fa-users-cog mr-3 w-4 text-bgray-500"></i> Manajemen User
                </a>
                @endcan
                <hr class="my-2 border-bgray-200 dark:border-darkblack-400">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center px-4 py-2 text-left text-sm text-error-200 hover:bg-bgray-100 dark:hover:bg-darkblack-400">
                        <i class="fa fa-sign-out-alt mr-3 w-4"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Header -->
<header class="header-mobile fixed top-0 left-0 right-0 z-30 flex items-center justify-between bg-white dark:bg-darkblack-600 px-4 py-4 shadow-sm xl:hidden">
    <!-- Mobile Menu Button -->
    <button type="button" class="drawer-btn flex items-center justify-center h-10 w-10 rounded-lg hover:bg-bgray-100 dark:hover:bg-darkblack-500">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-bgray-700 dark:text-white">
            <path d="M3 12H21M3 6H21M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>

    <!-- Logo -->
    <a href="{{ route('dashboard') }}" class="flex items-center">
        <span class="text-xl font-bold text-success-300">Kendaraan</span>
        <span class="text-lg font-medium text-bgray-900 dark:text-white">KAS</span>
    </a>

    <!-- Mobile Right Actions -->
    <div class="flex items-center space-x-2">
        <!-- Notifications Mobile -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" type="button" class="relative flex h-10 w-10 items-center justify-center rounded-full hover:bg-bgray-100 dark:hover:bg-darkblack-500">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 2C6.68629 2 4 4.68629 4 8V11.5858L3.29289 12.2929C3.00517 12.5806 2.92797 13.0124 3.09922 13.3833C3.27047 13.7542 3.65283 14 4 14H16C16.3472 14 16.7295 13.7542 16.9008 13.3833C17.072 13.0124 16.9948 12.5806 16.7071 12.2929L16 11.5858V8C16 4.68629 13.3137 2 10 2Z" fill="#A0AEC0"/>
                    <path d="M10 18C11.6569 18 13 16.6569 13 15H7C7 16.6569 8.34315 18 10 18Z" fill="#22C55E"/>
                </svg>
                @if($notificationCount > 0)
                <span class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-error-200 text-[10px] text-white">{{ $notificationCount > 9 ? '9+' : $notificationCount }}</span>
                @endif
            </button>

            <!-- Mobile Notification Dropdown -->
            <div x-show="open" @click.away="open = false" x-transition class="fixed left-4 right-4 top-16 rounded-lg bg-white shadow-lg dark:bg-darkblack-500 border border-bgray-200 dark:border-darkblack-400 overflow-hidden z-50">
                <div class="px-4 py-3 border-b border-bgray-200 dark:border-darkblack-400 flex items-center justify-between">
                    <h4 class="font-semibold text-bgray-900 dark:text-white">Notifikasi</h4>
                    @if($notificationCount > 0)
                    <span class="text-xs bg-error-100 text-error-300 px-2 py-1 rounded-full">{{ $notificationCount }} baru</span>
                    @endif
                </div>

                <div class="max-h-64 overflow-y-auto">
                    @forelse($notifications->take(5) as $notification)
                    <a href="{{ $notification['url'] }}" class="block px-4 py-3 hover:bg-bgray-50 dark:hover:bg-darkblack-400 border-b border-bgray-100 dark:border-darkblack-400 last:border-0">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-1">
                                @if($notification['color'] === 'error')
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-error-50">
                                    <i class="fa {{ $notification['icon'] }} text-error-300 text-xs"></i>
                                </span>
                                @elseif($notification['color'] === 'warning')
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-warning-50">
                                    <i class="fa {{ $notification['icon'] }} text-warning-300 text-xs"></i>
                                </span>
                                @else
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-success-50">
                                    <i class="fa {{ $notification['icon'] }} text-success-300 text-xs"></i>
                                </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-bgray-900 dark:text-white">{{ $notification['title'] }}</p>
                                <p class="text-xs text-bgray-600 dark:text-bgray-300 truncate">{{ $notification['message'] }}</p>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="px-4 py-6 text-center">
                        <p class="text-sm text-bgray-500 dark:text-bgray-400">Tidak ada notifikasi</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Theme Toggle Mobile -->
        <button type="button" id="theme-toggle-mobile" class="flex h-10 w-10 items-center justify-center rounded-full hover:bg-bgray-100 dark:hover:bg-darkblack-500">
            <svg class="hidden dark:block w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
            </svg>
            <svg class="block dark:hidden w-5 h-5 text-bgray-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
        </button>
    </div>
</header>

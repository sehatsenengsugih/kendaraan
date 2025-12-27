<header class="header-wrapper fixed z-20 hidden w-full items-center justify-between bg-white dark:bg-darkblack-600 px-6 py-5 shadow-sm xl:flex">
    <div class="flex items-center space-x-4">
        <!-- Drawer Toggle Button (Desktop) -->
        <button type="button" class="drawer-btn hidden xl:flex items-center justify-center h-10 w-10 rounded-lg hover:bg-bgray-100 dark:hover:bg-darkblack-500 transition-colors" title="Toggle Sidebar (Ctrl+B)">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-bgray-600 dark:text-bgray-300">
                <path d="M3 12H21M3 6H21M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <!-- Search Bar -->
        <div class="relative hidden lg:block">
            <span class="absolute left-4 top-1/2 -translate-y-1/2">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="9.78639" cy="9.78614" r="8.23951" stroke="#A0AEC0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15.5176 15.9448L18.7479 19.1668" stroke="#A0AEC0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <input type="text" id="search" placeholder="Cari kendaraan, plat nomor... (Ctrl+K)" class="w-[300px] rounded-lg border border-bgray-200 py-3 pl-12 pr-4 text-sm text-bgray-800 placeholder:text-bgray-500 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white dark:placeholder:text-bgray-300">
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
        <div class="relative">
            <button type="button" class="relative flex h-10 w-10 items-center justify-center rounded-full bg-bgray-100 hover:bg-bgray-200 dark:bg-darkblack-500 dark:hover:bg-darkblack-400 transition-colors">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 2C6.68629 2 4 4.68629 4 8V11.5858L3.29289 12.2929C3.00517 12.5806 2.92797 13.0124 3.09922 13.3833C3.27047 13.7542 3.65283 14 4 14H16C16.3472 14 16.7295 13.7542 16.9008 13.3833C17.072 13.0124 16.9948 12.5806 16.7071 12.2929L16 11.5858V8C16 4.68629 13.3137 2 10 2Z" fill="#A0AEC0"/>
                    <path d="M10 18C11.6569 18 13 16.6569 13 15H7C7 16.6569 8.34315 18 10 18Z" fill="#22C55E"/>
                </svg>
                <!-- Notification Badge -->
                <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-error-200 text-xs text-white">3</span>
            </button>
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

            <!-- Dropdown Menu -->
            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 rounded-lg bg-white py-2 shadow-lg dark:bg-darkblack-500 border border-bgray-200 dark:border-darkblack-400">
                <div class="px-4 py-2 border-b border-bgray-200 dark:border-darkblack-400">
                    <p class="text-sm font-semibold text-bgray-900 dark:text-white">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-bgray-500 dark:text-bgray-300">{{ Auth::user()->email ?? '' }}</p>
                </div>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-bgray-700 hover:bg-bgray-100 dark:text-white dark:hover:bg-darkblack-400">
                    <i class="fa fa-user mr-3 w-4 text-bgray-500"></i> Profil Saya
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-bgray-700 hover:bg-bgray-100 dark:text-white dark:hover:bg-darkblack-400">
                    <i class="fa fa-cog mr-3 w-4 text-bgray-500"></i> Pengaturan
                </a>
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
        <!-- Theme Toggle Mobile -->
        <button type="button" id="theme-toggle-mobile" class="flex h-10 w-10 items-center justify-center rounded-full hover:bg-bgray-100 dark:hover:bg-darkblack-500">
            <svg class="hidden dark:block w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
            </svg>
            <svg class="block dark:hidden w-5 h-5 text-bgray-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
        </button>

        <!-- User Avatar -->
        <div class="h-10 w-10 overflow-hidden rounded-full bg-success-100">
            <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'User') . '&background=22C55E&color=fff' }}" alt="User" class="h-full w-full object-cover">
        </div>
    </div>
</header>

<!-- Spacer for fixed header -->
<div class="h-[72px] xl:h-[88px]"></div>

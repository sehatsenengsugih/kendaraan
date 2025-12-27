<header class="header-wrapper fixed z-20 hidden w-full items-center justify-between bg-white dark:bg-darkblack-600 px-6 py-5 shadow-sm xl:flex">
    <div class="flex items-center space-x-4">
        <!-- Drawer Toggle Button (Mobile) -->
        <button type="button" class="drawer-btn xl:hidden">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
            <input type="text" id="search" placeholder="Cari kendaraan, plat nomor..." class="w-[300px] rounded-lg border border-bgray-200 py-3 pl-12 pr-4 text-sm text-bgray-800 placeholder:text-bgray-500 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white dark:placeholder:text-bgray-300">
        </div>
    </div>

    <div class="flex items-center space-x-6">
        <!-- Notifications -->
        <div class="relative">
            <button type="button" class="relative flex h-10 w-10 items-center justify-center rounded-full bg-bgray-100 dark:bg-darkblack-500">
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
            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 rounded-lg bg-white py-2 shadow-lg dark:bg-darkblack-500">
                <a href="#" class="block px-4 py-2 text-sm text-bgray-700 hover:bg-bgray-100 dark:text-white dark:hover:bg-darkblack-400">
                    Profil Saya
                </a>
                <a href="#" class="block px-4 py-2 text-sm text-bgray-700 hover:bg-bgray-100 dark:text-white dark:hover:bg-darkblack-400">
                    Pengaturan
                </a>
                <hr class="my-2 border-bgray-200 dark:border-darkblack-400">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-error-200 hover:bg-bgray-100 dark:hover:bg-darkblack-400">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- Spacer for fixed header -->
<div class="h-[88px] xl:block hidden"></div>

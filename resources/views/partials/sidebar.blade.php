<aside class="sidebar-wrapper fixed top-0 z-30 block h-full w-[308px] bg-white dark:bg-darkblack-600 sm:hidden xl:block transition-all duration-300">
    <!-- Logo -->
    <div class="sidebar-header relative z-30 flex h-[108px] w-full items-center border-b border-r border-b-bgray-200 border-r-bgray-200 pl-[50px] dark:border-darkblack-400">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <span class="text-2xl font-bold text-accent-300">Kendaraan</span>
            <span class="text-xl font-medium text-bgray-900 dark:text-white">KAS</span>
        </a>
        <!-- Close Button (Mobile) -->
        <button type="button" class="drawer-btn absolute right-4 top-1/2 -translate-y-1/2 xl:hidden flex h-8 w-8 items-center justify-center rounded-lg hover:bg-bgray-100 dark:hover:bg-darkblack-500">
            <svg class="w-5 h-5 text-bgray-600 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <!-- Toggle Button (Desktop) - moved inside sidebar boundary -->
        <button type="button" class="drawer-btn absolute right-2 top-1/2 -translate-y-1/2 hidden xl:flex items-center justify-center w-8 h-8 bg-accent-300 rounded-full hover:bg-accent-400 transition-colors" title="Toggle Sidebar (Ctrl+B)">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
    </div>

    <!-- Sidebar Body -->
    <div class="sidebar-body overflow-style-none relative z-30 h-[calc(100vh-108px)] w-full overflow-y-scroll pl-[48px] pt-[14px]">
        <div class="nav-wrapper mb-[36px] pr-[50px]">
            <!-- Menu Utama -->
            <div class="item-wrapper mb-5">
                <h4 class="border-b border-bgray-200 text-sm font-medium leading-7 text-bgray-700 dark:border-darkblack-400 dark:text-bgray-50">
                    Menu
                </h4>
                <ul class="mt-2.5">
                    <!-- Dashboard -->
                    <li class="item py-[11px] text-bgray-900 dark:text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="18" height="21" viewBox="0 0 18 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="path-1" d="M0 8.84719C0 7.99027 0.366443 7.17426 1.00691 6.60496L6.34255 1.86217C7.85809 0.515019 10.1419 0.515019 11.6575 1.86217L16.9931 6.60496C17.6336 7.17426 18 7.99027 18 8.84719V17C18 19.2091 16.2091 21 14 21H4C1.79086 21 0 19.2091 0 17V8.84719Z" fill="#1A202C"/>
                                            <path class="path-2" d="M5 17C5 14.7909 6.79086 13 9 13C11.2091 13 13 14.7909 13 17V21H5V17Z" fill="#22C55E"/>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg font-medium leading-none">Dashboard</span>
                                </div>
                            </div>
                        </a>
                    </li>

                    @can('access-main-menu')
                    <!-- Kendaraan -->
                    <li class="item py-[11px] text-bgray-900 dark:text-white {{ request()->routeIs('kendaraan.*') ? 'active' : '' }}">
                        <a href="{{ route('kendaraan.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="path-1" d="M17 8H3C1.89543 8 1 8.89543 1 10V16C1 17.1046 1.89543 18 3 18H17C18.1046 18 19 17.1046 19 16V10C19 8.89543 18.1046 8 17 8Z" fill="#1A202C"/>
                                            <path class="path-2" d="M4 14C4.55228 14 5 13.5523 5 13C5 12.4477 4.55228 12 4 12C3.44772 12 3 12.4477 3 13C3 13.5523 3.44772 14 4 14Z" fill="#22C55E"/>
                                            <path class="path-2" d="M16 14C16.5523 14 17 13.5523 17 13C17 12.4477 16.5523 12 16 12C15.4477 12 15 12.4477 15 13C15 13.5523 15.4477 14 16 14Z" fill="#22C55E"/>
                                            <path class="path-1" d="M4 8L5.5 3H14.5L16 8" stroke="#1A202C" stroke-width="2"/>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg font-medium leading-none">Kendaraan</span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Pajak -->
                    <li class="item py-[11px] text-bgray-900 dark:text-white {{ request()->routeIs('pajak.*') ? 'active' : '' }}">
                        <a href="{{ route('pajak.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="path-1" d="M4 2C2.89543 2 2 2.89543 2 4V16C2 17.1046 2.89543 18 4 18H16C17.1046 18 18 17.1046 18 16V4C18 2.89543 17.1046 2 16 2H4Z" fill="#1A202C"/>
                                            <path class="path-2" d="M6 6H14M6 10H14M6 14H10" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg font-medium leading-none">Pajak</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    @endcan

                    @can('manage-servis')
                    <!-- Servis -->
                    <li class="item py-[11px] text-bgray-900 dark:text-white {{ request()->routeIs('servis.*') ? 'active' : '' }}">
                        <a href="{{ route('servis.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="path-1" d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" fill="#1A202C"/>
                                            <path class="path-2" d="M10 6V10L13 13" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg font-medium leading-none">Servis</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    @endcan

                    <!-- Kalender -->
                    <li class="item py-[11px] text-bgray-900 dark:text-white {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                        <a href="{{ route('calendar.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="path-1" d="M3 5C3 3.89543 3.89543 3 5 3H15C16.1046 3 17 3.89543 17 5V15C17 16.1046 16.1046 17 15 17H5C3.89543 17 3 16.1046 3 15V5Z" fill="#1A202C"/>
                                            <path class="path-2" d="M7 1V3M13 1V3M3 7H17" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round"/>
                                            <circle class="path-2" cx="10" cy="11" r="1.5" fill="#22C55E"/>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg font-medium leading-none">Kalender</span>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Master Data -->
            @can('access-master-data')
            <div class="item-wrapper mb-5">
                <h4 class="border-b border-bgray-200 text-sm font-medium leading-7 text-bgray-700 dark:border-darkblack-400 dark:text-bgray-50">
                    Master Data
                </h4>
                <ul class="mt-2.5">
                    <!-- Garasi -->
                    <li class="item py-[11px] text-bgray-900 dark:text-white {{ request()->routeIs('garasi.*') ? 'active' : '' }}">
                        <a href="{{ route('garasi.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="path-1" d="M3 10L10 3L17 10V17C17 17.5304 16.7893 18.0391 16.4142 18.4142C16.0391 18.7893 15.5304 19 15 19H5C4.46957 19 3.96086 18.7893 3.58579 18.4142C3.21071 18.0391 3 17.5304 3 17V10Z" fill="#1A202C"/>
                                            <path class="path-2" d="M8 19V12H12V19" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg font-medium leading-none">Garasi</span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Merk -->
                    <li class="item py-[11px] text-bgray-900 dark:text-white {{ request()->routeIs('merk.*') ? 'active' : '' }}">
                        <a href="{{ route('merk.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="path-1" d="M2 4C2 2.89543 2.89543 2 4 2H16C17.1046 2 18 2.89543 18 4V6C18 7.10457 17.1046 8 16 8H4C2.89543 8 2 7.10457 2 6V4Z" fill="#1A202C"/>
                                            <path class="path-2" d="M6 5H14" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round"/>
                                            <path class="path-1" d="M2 14C2 12.8954 2.89543 12 4 12H16C17.1046 12 18 12.8954 18 14V16C18 17.1046 17.1046 18 16 18H4C2.89543 18 2 17.1046 2 16V14Z" fill="#1A202C"/>
                                            <path class="path-2" d="M6 15H14" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg font-medium leading-none">Merk</span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Paroki -->
                    <li class="item py-[11px] text-bgray-900 dark:text-white {{ request()->routeIs('paroki.*') ? 'active' : '' }}">
                        <a href="{{ route('paroki.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="path-1" d="M10 2L3 8V18H17V8L10 2Z" fill="#1A202C"/>
                                            <path class="path-2" d="M10 2V6M10 18V14M7 18V14H13V18" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg font-medium leading-none">Paroki</span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Lembaga -->
                    <li class="item py-[11px] text-bgray-900 dark:text-white {{ request()->routeIs('lembaga.*') ? 'active' : '' }}">
                        <a href="{{ route('lembaga.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="path-1" d="M3 4C3 2.89543 3.89543 2 5 2H15C16.1046 2 17 2.89543 17 4V18H3V4Z" fill="#1A202C"/>
                                            <path class="path-2" d="M6 6H8M6 10H8M6 14H8M12 6H14M12 10H14M12 14H14" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg font-medium leading-none">Lembaga</span>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            @endcan

            <!-- Pengaturan -->
            @can('manage-users')
            <div class="item-wrapper mb-5">
                <h4 class="border-b border-bgray-200 text-sm font-medium leading-7 text-bgray-700 dark:border-darkblack-400 dark:text-bgray-50">
                    Pengaturan
                </h4>
                <ul class="mt-2.5">
                    <!-- User Management -->
                    <li class="item py-[11px] text-bgray-900 dark:text-white {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="path-1" d="M14 16C14 13.7909 12.2091 12 10 12C7.79086 12 6 13.7909 6 16" fill="#1A202C"/>
                                            <path class="path-2" d="M10 10C11.6569 10 13 8.65685 13 7C13 5.34315 11.6569 4 10 4C8.34315 4 7 5.34315 7 7C7 8.65685 8.34315 10 10 10Z" fill="#22C55E"/>
                                            <path class="path-1" d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="#1A202C" stroke-width="1.5"/>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg font-medium leading-none">Pengguna</span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- Audit Log -->
                    @can('view-audit-logs')
                    <li class="item py-[11px] text-bgray-900 dark:text-white {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
                        <a href="{{ route('audit-logs.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="path-1" d="M4 2C2.89543 2 2 2.89543 2 4V16C2 17.1046 2.89543 18 4 18H16C17.1046 18 18 17.1046 18 16V4C18 2.89543 17.1046 2 16 2H4Z" fill="#1A202C"/>
                                            <path class="path-2" d="M6 6H14M6 10H14M6 14H10" stroke="#22C55E" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg font-medium leading-none">Audit Log</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
            @endcan
        </div>

        <!-- Bottom Section: User Profile & Actions -->
        <div class="absolute bottom-0 left-0 right-0 border-t border-bgray-200 bg-white dark:border-darkblack-400 dark:bg-darkblack-600">
            <!-- User Profile -->
            <div class="flex items-center gap-3 p-4 border-b border-bgray-100 dark:border-darkblack-400">
                <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-xl border border-bgray-200 dark:border-darkblack-400">
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="h-full w-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-bgray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-bgray-500 dark:text-bgray-400 truncate">{{ Auth::user()->email }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="flex-shrink-0 rounded-lg p-2 text-bgray-500 hover:bg-bgray-100 dark:hover:bg-darkblack-500" title="Edit Profil">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </a>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between p-3">
                <!-- Theme Toggle -->
                <button type="button" id="theme-toggle-sidebar" class="flex items-center space-x-2 rounded-lg px-3 py-2 text-sm font-medium text-bgray-700 hover:bg-bgray-100 dark:text-white dark:hover:bg-darkblack-500 transition-colors">
                    <svg class="hidden dark:block w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    <svg class="block dark:hidden w-5 h-5 text-bgray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                    <span class="hidden dark:inline">Light</span>
                    <span class="inline dark:hidden">Dark</span>
                </button>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center space-x-2 rounded-lg px-3 py-2 text-sm font-medium text-error-200 hover:bg-error-50 dark:hover:bg-error-900/20 transition-colors" title="Keluar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

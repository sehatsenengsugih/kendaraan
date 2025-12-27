<aside class="sidebar-wrapper fixed top-0 z-30 block h-full w-[308px] bg-white dark:bg-darkblack-600 sm:hidden xl:block">
    <!-- Logo -->
    <div class="sidebar-header relative z-30 flex h-[108px] w-full items-center border-b border-r border-b-bgray-200 border-r-bgray-200 pl-[50px] dark:border-darkblack-400">
        <a href="{{ route('dashboard') }}">
            <span class="text-2xl font-bold text-success-300">Kendaraan</span>
            <span class="text-xl font-medium text-bgray-900 dark:text-white">KAS</span>
        </a>
        <button type="button" class="drawer-btn absolute right-0 top-auto" title="Ctrl+b">
            <span>
                <svg width="16" height="40" viewBox="0 0 16 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 10C0 4.47715 4.47715 0 10 0H16V40H10C4.47715 40 0 35.5228 0 30V10Z" fill="#22C55E"/>
                    <path d="M10 15L6 20.0049L10 25.0098" stroke="#ffffff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </button>
    </div>

    <!-- Sidebar Body -->
    <div class="sidebar-body overflow-style-none relative z-30 h-screen w-full overflow-y-scroll pb-[200px] pl-[48px] pt-[14px]">
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

                    <!-- Penugasan -->
                    <li class="item py-[11px] text-bgray-900 dark:text-white {{ request()->routeIs('penugasan.*') ? 'active' : '' }}">
                        <a href="{{ route('penugasan.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="path-1" d="M14 16C14 13.7909 12.2091 12 10 12C7.79086 12 6 13.7909 6 16" fill="#1A202C"/>
                                            <path class="path-2" d="M10 10C11.6569 10 13 8.65685 13 7C13 5.34315 11.6569 4 10 4C8.34315 4 7 5.34315 7 7C7 8.65685 8.34315 10 10 10Z" fill="#22C55E"/>
                                            <path class="path-1" d="M10 18C14.4183 18 18 14.4183 18 10C18 5.58172 14.4183 2 10 2C5.58172 2 2 5.58172 2 10C2 14.4183 5.58172 18 10 18Z" stroke="#1A202C" stroke-width="1.5"/>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg font-medium leading-none">Penugasan</span>
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
                </ul>
            </div>

            <!-- Master Data -->
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
                </ul>
            </div>

            <!-- Pengaturan -->
            @can('manage-users')
            <div class="item-wrapper mb-5">
                <h4 class="border-b border-bgray-200 text-sm font-medium leading-7 text-bgray-700 dark:border-darkblack-400 dark:text-bgray-50">
                    Pengaturan
                </h4>
                <ul class="mt-2.5">
                    <!-- User Management -->
                    <li class="item py-[11px] text-bgray-900 dark:text-white">
                        <a href="#">
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
                    <li class="item py-[11px] text-bgray-900 dark:text-white">
                        <a href="#">
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
    </div>
</aside>

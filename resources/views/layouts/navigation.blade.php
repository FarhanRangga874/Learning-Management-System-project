<nav x-data="{ 
        open: false, 
        showSearch: false,
        isLanding: {{ request()->routeIs('front.index') ? 'true' : 'false' }} 
        }" 
    @scroll.window="showSearch = (window.pageYOffset > 400)"
    class="w-full top-0 z-50 transition-all duration-300 border-b"
    :class="{
        'fixed': isLanding, 
        'sticky': !isLanding,
        'bg-white border-gray-200 shadow-sm': !isLanding || showSearch,
        'bg-transparent border-transparent': isLanding && !showSearch
    }">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 relative">
            
            {{-- BAGIAN KIRI: LOGO + SEARCH BAR --}}
            <div class="flex items-center gap-6 flex-1">
                
                {{-- Logo --}}
            <div class="shrink-0 flex items-center">
    <a href="/" class="flex items-center group hover:opacity-80">

        <!-- LOGO -->
        <x-application-logo
            class="block h-12 w-auto fill-current text-indigo-600
                   transition-transform duration-200
                   translate-y-[1px] group-hover:scale-105
                   mr-1.5"
        />

        <!-- WORDMARK -->
        <div class="hidden sm:flex flex-col leading-none">
            <span
                class="font-black text-xl tracking-tighter font-sans"
                :class="(!isLanding || showSearch) ? 'text-gray-900' : 'text-slate-800'">
                <span class="text-indigo-600">AL</span>DI
            </span>

            <span class="mt-0.5 text-[11px] font-medium tracking-wide text-gray-500">
                Akses Literasi Digital
            </span>
        </div>

    </a>
</div>

                {{-- SEARCH BAR (DESKTOP) --}}
                @if(request()->routeIs('front.index'))
                    <div class="hidden md:block w-full max-w-sm transition-all duration-500 ease-out"
                        x-show="showSearch"
                        x-transition:enter="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="opacity-0 -translate-x-4"
                        style="display: none;">
                        
                        <form action="{{ route('front.index') }}" method="GET" class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" placeholder="Cari kelas..." 
                                class="block w-full pl-11 pr-4 py-2 bg-gray-100 border-transparent rounded-full text-sm text-gray-700 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:border-transparent transition-all shadow-sm group-hover:bg-gray-50">
                        </form>
                    </div>
                @endif

            </div>

            {{-- BAGIAN KANAN: MENU --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-6">
                
                {{-- Main Menu --}}
                <div class="flex items-center gap-6">

                    <a href="{{ route('front.index') }}" class="text-sm font-medium transition-colors hover:text-indigo-600" 
                        :class="(!isLanding || showSearch) ? 'text-gray-600' : 'text-slate-700'">
                        {{ __('Katalog') }}
                    </a>

                    <a href="#" class="text-sm font-medium transition-colors hover:text-indigo-600" 
                        :class="(!isLanding || showSearch) ? 'text-gray-600' : 'text-slate-700'">
                        {{ __('Panduan') }}
                    </a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium transition-colors hover:text-indigo-600"
                            :class="(!isLanding || showSearch) ? 'text-gray-600' : 'text-slate-700'">
                            {{ __('Dashboard') }}
                        </a>



                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.courses.index') }}" 
                                class="text-sm font-bold text-amber-600 hover:text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full transition-colors border border-amber-100 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ __('Admin') }}
                            </a>
                        @endif
                    @endauth
                </div>

                {{-- User Dropdown / Auth Buttons --}}
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 transition hover:opacity-80 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs border border-indigo-200">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-100">
                                Hai, <span class="font-bold text-gray-800">{{ Auth::user()->name }}</span>
                            </div>
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-red-600 hover:bg-red-50">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-indigo-600 transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-full hover:bg-indigo-700 transition shadow-md shadow-indigo-200">Daftar</a>
                    </div>
                @endauth
            </div>

            {{-- Hamburger (Mobile) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Responsive Navigation Menu --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-100 bg-white shadow-lg">
        <div class="pt-2 pb-3 space-y-1">
            
            {{-- Mobile Link Panduan --}}
            <x-responsive-nav-link href="#" :active="false">
                {{ __('Panduan') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('front.index')" :active="request()->routeIs('front.index')">
                {{ __('Katalog Kelas') }}
            </x-responsive-nav-link>
            
            @if(request()->routeIs('front.index'))
                <div class="px-4 py-2">
                    <form action="{{ route('front.index') }}" method="GET">
                        <input type="text" name="search" placeholder="Cari kelas..." class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                    </form>
                </div>
            @endif
        </div>

        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Kelas Saya') }}
                    </x-responsive-nav-link>
                    
                    @if(Auth::user()->role === 'admin')
                        <x-responsive-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.*')" class="text-amber-600 font-bold bg-amber-50">
                            {{ __('Admin Panel') }}
                        </x-responsive-nav-link>
                    @endif

                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-4 border-t border-gray-200 px-4 space-y-3">
                <a href="{{ route('login') }}" class="block w-full text-center py-2.5 text-sm font-bold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="block w-full text-center py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-md">
                    Daftar Sekarang
                </a>
            </div>
        @endauth
    </div>
</nav>
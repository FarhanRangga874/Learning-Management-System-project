<nav x-data="{ open: false, scrolled: false }" 
    @scroll.window="scrolled = (window.pageYOffset > 20)"
    :class="{ 'bg-white/90 backdrop-blur-md shadow-sm': scrolled, 'bg-white border-b border-gray-100': !scrolled }"
    class="sticky top-0 z-50 transition-all duration-300 ease-in-out">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <div class="shrink-0 flex items-center">
                    <a href="/" class="transition hover:scale-105 duration-200">
                        <x-application-logo class="block h-9 w-auto fill-current text-indigo-600" />
                    </a>
                </div>

                <div class="hidden space-x-6 sm:flex sm:items-center">
                    <x-nav-link :href="route('front.index')" :active="request()->routeIs('front.index')" class="text-sm font-medium hover:text-indigo-600 transition-colors">
                        {{ __('Home') }}
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-sm font-medium hover:text-indigo-600 transition-colors">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        @if(Auth::user()->role === 'admin')
                            <x-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.*')" class="text-sm font-medium text-amber-600 hover:text-amber-700">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ __('Admin Panel') }}
                                </span>
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-4">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <form action="{{ route('dev.toggle') }}" method="POST" class="mr-2">
                            @csrf
                            <button type="submit" 
                                class="group relative inline-flex items-center justify-center px-3 py-1 text-xs font-bold rounded-full border transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 {{ session('dev_mode') ? 'bg-red-50 text-red-600 border-red-200 hover:bg-red-100 focus:ring-red-500' : 'bg-gray-50 text-gray-500 border-gray-200 hover:bg-gray-100 focus:ring-gray-400' }}">
                                <span class="flex items-center gap-1.5">
                                    <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ session('dev_mode') ? 'bg-red-400' : 'hidden' }}"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 {{ session('dev_mode') ? 'bg-red-500' : 'bg-gray-400' }}"></span>
                                    </span>
                                    {{ session('dev_mode') ? 'DEV ON' : 'DEV OFF' }}
                                </span>
                            </button>
                        </form>
                    @endif

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-gray-600 bg-gray-50 hover:bg-gray-100 hover:text-gray-900 focus:outline-none transition duration-150 ease-in-out">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden md:inline-block">{{ Auth::user()->name }}</span>
                                <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-gray-100 text-xs text-gray-500">
                                {{ __('Signed in as') }} <br>
                                <span class="font-bold text-gray-700">{{ Auth::user()->email }}</span>
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
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors px-3 py-2">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white transition-all duration-200 bg-indigo-600 border border-transparent rounded-full shadow-sm hover:bg-indigo-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                            Register Now
                        </a>
                    </div>
                @endauth
            </div>

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

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-100 bg-white shadow-lg">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('front.index')" :active="request()->routeIs('front.index')">
                {{ __('Katalog Kelas') }}
            </x-responsive-nav-link>
            
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Kelas Saya') }}
                </x-responsive-nav-link>
                
                @if(Auth::user()->role === 'admin')
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                            Administrator
                        </div>
                        <x-responsive-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.*')" class="text-amber-600">
                            {{ __('Admin Panel') }}
                        </x-responsive-nav-link>
                        
                        <div class="px-4 py-2">
                            <form action="{{ route('dev.toggle') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md border {{ session('dev_mode') ? 'bg-red-50 text-red-700 border-red-200' : 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                    {{ session('dev_mode') ? '🔴 Matikan Dev Mode' : '⚪ Nyalakan Dev Mode' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endauth
        </div>

        <div class="pt-4 pb-4 border-t border-gray-200 bg-gray-50">
            @auth
                <div class="flex items-center px-4 mb-3">
                    <div class="shrink-0">
                         <div class="w-10 h-10 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-lg">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-3 px-4 pb-2">
                    <a href="{{ route('login') }}" class="block text-center w-full px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="block text-center w-full px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Register Account
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
<nav x-data="{ open: false }" class="bg-[#1a1412] border-b border-[#3b2f2b]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-decoration-none">
                        <i class="bi bi-rocket-takeoff" style="color: #ff6b2b; font-size: 1.4rem;"></i>
                        <span class="font-bold text-lg hidden sm:block" style="background: linear-gradient(135deg, #ff6b2b 0%, #e63946 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">CODEBASE FOODS</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:flex sm:ms-8 items-center">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('home') ? 'text-[#ff6b2b]' : 'text-[#d4c5bf] hover:text-[#fdf5f1]' }}">
                        <i class="bi bi-house me-1"></i> Home
                    </a>
                    <a href="{{ route('restaurants.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('restaurants.*') ? 'text-[#ff6b2b]' : 'text-[#d4c5bf] hover:text-[#fdf5f1]' }}">
                        <i class="bi bi-shop me-1"></i> Restaurants
                    </a>
                    @auth
                        <a href="{{ route('orders.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('orders.*') ? 'text-[#ff6b2b]' : 'text-[#d4c5bf] hover:text-[#fdf5f1]' }}">
                            <i class="bi bi-receipt me-1"></i> My Orders
                        </a>
                        <a href="{{ route('cart.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('cart.*') ? 'text-[#ff6b2b]' : 'text-[#d4c5bf] hover:text-[#fdf5f1]' }}">
                            <i class="bi bi-cart me-1"></i> Cart
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-[#d4c5bf] bg-[#241c19] hover:text-[#fdf5f1] focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-[#d4c5bf] hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                        {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}" class="text-[#d4c5bf] hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                        {{ __('Register') }}
                    </a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-[#d4c5bf] hover:text-[#fdf5f1] hover:bg-[#241c19] focus:outline-none focus:bg-[#241c19] focus:text-[#fdf5f1] transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                <i class="bi bi-house me-2"></i> Home
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('restaurants.index')" :active="request()->routeIs('restaurants.*')">
                <i class="bi bi-shop me-2"></i> Restaurants
            </x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                    <i class="bi bi-receipt me-2"></i> My Orders
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                    <i class="bi bi-cart me-2"></i> Cart
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-[#3b2f2b]">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-[#fdf5f1]">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-[#d4c5bf]">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1 px-4">
                    <a href="{{ route('login') }}" class="block text-[#d4c5bf] hover:text-white">
                        {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}" class="block text-[#d4c5bf] hover:text-white">
                        {{ __('Register') }}
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

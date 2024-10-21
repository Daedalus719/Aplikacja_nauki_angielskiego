<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Nav First Row -->
        <div class="grid grid-cols-3 h-16">
            <div class="flex justify-start items-center">
                <!-- -->
            </div>

            <div class="middle-nav-container" :class="{ 'hidden': open }">
                <div class="middle-nav space-x-8 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Strona główna') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dictionary')" :active="request()->routeIs('dictionary')">
                        {{ __('Słownik') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="flex justify-end items-center">
                @if (Route::has('login'))
                    <nav class="flex justify-end">
                        @auth
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 mt-3 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Mój profil') }}
                                    </x-dropdown-link>
                                    @if (Auth::user()->usertype === 'Admin')
                                        <x-dropdown-link :href="route('admin.panel')">
                                            {{ __('Panel Admina') }}
                                        </x-dropdown-link>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                            {{ __('Wyloguj się') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @else
                            <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-black">
                                Zaloguj się
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-md px-3 py-2 text-black">
                                    Zarejestruj się
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </div>

        <!-- Nav second row -->
        <div class="grid grid-cols-3 py-4" :class="{ 'hidden': open }">
            <div class="flex flex-col items-center justify-center text-center">
                <h1 class="text-xl font-bold text-gray-900">Teoria</h1>
                <div class="flex space-x-4 justify-center">
                    <x-nav-link :href="route('course.index')" :active="request()->routeIs('course.index')">
                        {{ __('Słownictwo') }}
                    </x-nav-link>
                    <x-nav-link :href="route('irregular-verbs.index')" :active="request()->routeIs('irregular-verbs.index')">
                        {{ __('Czasowniki nieregularne') }}
                    </x-nav-link>
                    <x-nav-link :href="route('sections.index')" :active="request()->routeIs('sections.index')">
                        {{ __('Reguły tworzenia zdań') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="flex justify-center items-center text-center">
                <x-nav-link :href="route('games.index')" :active="request()->routeIs('games.index')">
                    {{ __('Gry') }}
                </x-nav-link>
            </div>

            <div class="flex flex-col items-center justify-center text-center">
                <h1 class="text-xl font-bold text-gray-900">Praktyka</h1>
                <div class="flex space-x-4 justify-center">
                    <x-nav-link :href="route('tests.index')" :active="request()->routeIs('tests.index')">
                        {{ __('Testy z słownictwa') }}
                    </x-nav-link>
                    <x-nav-link :href="route('irregular-verbs.tasks')" :active="request()->routeIs('irregular-verbs.tasks')">
                        {{ __('Zadania z czasownikami nieregularnymi') }}
                    </x-nav-link>
                    <x-nav-link :href="route('sentence_game.index')" :active="request()->routeIs('sentence_game.index')">
                        {{ __('Zadania z reguł tworzenia zdań') }}
                    </x-nav-link>
                </div>
            </div>


            <!-- Hamburger Menu for Mobile -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Strona główna') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dictionary')" :active="request()->routeIs('dictionary')">
                    {{ __('Słownik') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('games.index')" :active="request()->routeIs('games.index')">
                    {{ __('Gry') }}
                </x-responsive-nav-link>
                <h2>{{ __('Teoria') }}</h2>
                <x-responsive-nav-link :href="route('course.index')" :active="request()->routeIs('course.index')">
                    {{ __('Słownictwo') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('irregular-verbs.index')" :active="request()->routeIs('irregular-verbs.index')">
                    {{ __('Czasowniki nieregularne') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('sections.index')" :active="request()->routeIs('sections.index')">
                    {{ __('Reguły tworzenia zdań') }}
                </x-responsive-nav-link>
                <h2>{{ __('Praktyka') }}</h2>
                <x-responsive-nav-link :href="route('tests.index')" :active="request()->routeIs('tests.index')">
                    {{ __('Testy z słownictwa') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('irregular-verbs.tasks')" :active="request()->routeIs('irregular-verbs.tasks')">
                    {{ __('Zadania z czasownikami nieregularnymi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('sentence_game.index')" :active="request()->routeIs('sentence_game.index')">
                    {{ __('Zadania z reguł tworzenia zdań') }}
                </x-responsive-nav-link>
            </div>

            @auth
                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')">
                            {{ __('Mój profil') }}
                        </x-responsive-nav-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                {{ __('Wyloguj się') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tasker') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for interactivity without custom JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Or use Laravel Vite for compilation -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>

<body class="bg-gray-100">
    <div id="app">
        <nav class="bg-white shadow" x-data="{ open: false }">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ Session::has('api_token') ? route('tasks.index') : route('login') }}"
                                class="font-bold text-xl text-gray-800">
                                Tasker
                            </a>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <!-- Left Side Navigation -->
                            @if (Session::has('api_token'))
                                <a href="{{ route('tasks.index') }}"
                                    class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    {{ __('My Tasks') }}
                                </a>
                                @if (Session::get('user')['is_admin'])
                                    <a href="{{ route('users.index') }}"
                                        class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                        {{ __('Admin Tab') }}
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex items-center sm:hidden">
                        <button @click="open = !open" type="button"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset"
                            aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    <!-- Right Side Navigation -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        @if (Session::has('api_token'))
                            <div class="ml-3 relative" x-data="{ open: false }">
                                <div>
                                    <button @click="open = !open" type="button"
                                        class="bg-white flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2"
                                        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                        <span class="sr-only">Open user menu</span>
                                        <span
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                            {{ Session::get('user')['name'] ?? 'User' }}
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>
                                </div>

                                <!-- Dropdown menu -->
                                <div x-show="open" @click.away="open = false"
                                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                    role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                    tabindex="-1">
                                    <a href="{{ route('profile') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        {{ __('My Profile') }}
                                    </a>

                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            role="menuitem">
                                            {{ __('Logout') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                                {{ __('Login') }}
                            </a>
                            <a href="{{ route('register') }}"
                                class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                                {{ __('Register') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="open" class="sm:hidden" id="mobile-menu">

                <div class="pt-4 pb-3 border-t border-gray-200">
                    @if (Session::has('api_token'))
                        <div class="flex items-center px-4">
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-800">
                                    {{ Session::get('user')['name'] ?? 'User' }}</div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <a href="{{ route('profile') }}"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                {{ __('My Profile') }}
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mt-3 space-y-1">
                            <a href="{{ route('login') }}"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                {{ __('Login') }}
                            </a>
                            <a href="{{ route('register') }}"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                {{ __('Register') }}
                            </a>
                        </div>
                    @endif
                </div>
                <div class="pt-2 pb-3 space-y-1">
                    @if (Session::has('api_token'))
                        <a href="{{ route('tasks.index') }}"
                            class="bg-indigo-50 border-indigo-500 text-indigo-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            {{ __('My Tasks') }}
                        </a>
                        @if (Session::get('user')['is_admin'])
                            <a href="{{ route('users.index') }}"
                                class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                                {{ __('Admin Tab') }}
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </nav>
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 flex justify-between items-center"
                role="alert">
                <span>{{ session('success') }}</span>
                <button class="text-green-700 hover:text-green-900 font-bold"
                    onclick="this.parentElement.style.display='none'">
                    ✕
                </button>
            </div>
        @endif
        <main class="py-4 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (!request()->routeIs('tasks.index') && !request()->routeIs('users.index') && !request()->routeIs('login') && !request()->routeIs('register'))
                <div class="mb-4">
                    @php
                        // Determine the parent route based on the current route
                        $backUrl = '/';

                        // For task routes
                        if (request()->routeIs('tasks.*')) {
                            $uid = Session::get('user')['id'];
                            $tid = Session::get('task') ? Session::get('task')['user_id'] : null;
                            if($uid==$tid){
                                $backUrl = route('tasks.index');
                            }
                            else{
                                $backUrl = route('users.show', $tid);
                            }
                        }
                        // For user routes
                        elseif (request()->routeIs('users.*')) {
                            $backUrl = route('users.index');
                        }
                        // Add more route groups as needed
                    @endphp

                    <a href="{{ $backUrl }}"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </a>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>

</html>

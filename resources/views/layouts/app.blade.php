<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Styles -->
  @livewireStyles

  @stack('styles')
  <script>
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
  </script>
</head>

<body class="font-sans antialiased">
  <x-banner />

  <div x-data="{ sidebarOpen: false, desktopSidebarOpen: true }" class="flex min-h-screen bg-gray-100 dark:bg-gray-900 overflow-x-hidden">
    <!-- Desktop Sidebar -->
    <aside 
        class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-lg dark:bg-gray-800 hidden md:block overflow-y-auto transition-transform duration-300 ease-in-out transform"
        :class="desktopSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        @include('sidebar')
    </aside>

    <!-- Mobile Sidebar (Off-canvas) -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-50 flex md:hidden" role="dialog" aria-modal="true" style="display: none;">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50" @click="sidebarOpen = false" x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
        
        <aside class="relative flex-1 flex flex-col max-w-xs w-full bg-white dark:bg-gray-800 transition ease-in-out duration-300 transform" x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
             <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            @include('sidebar')
        </aside>
    </div>

    <!-- Main Content -->
    <div 
        class="flex-1 flex flex-col w-full transition-all duration-300 ease-in-out"
        :class="desktopSidebarOpen ? 'md:ml-72' : 'md:ml-0'"
    >
        <!-- Mobile Header -->
        <div class="md:hidden flex items-center justify-between bg-white dark:bg-gray-800 shadow p-4 z-40">
             <button @click="sidebarOpen = true" class="-ml-2 p-2 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
             <a href="{{ Auth::user()->isAdmin ? route('admin.dashboard') : route('home') }}">
                <x-application-mark class="block h-8 w-auto" />
            </a>
        </div>

        <!-- Desktop Hamburger & Page Heading (Unified Bar) -->
        <div class="hidden md:flex items-center bg-white dark:bg-gray-800 shadow px-4 py-4 z-30 sticky top-0">
            <button @click="desktopSidebarOpen = !desktopSidebarOpen" class="p-2 rounded-md text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none transition">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            @if (isset($header))
                <div class="ml-4">
                    {{ $header }}
                </div>
            @endif
        </div>

        <!-- Mobile Page Heading (Fallback if header exists) -->
        @if (isset($header))
        <header class="bg-white shadow dark:bg-gray-800 md:hidden">
            <div class="px-4 py-6 sm:px-6 lg:px-8">
            {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main class="flex-1 p-6 overflow-x-hidden">
            {{ $slot }}
        </main>
    </div>
  </div>

  <x-sigsegv-core-dumped />

  @stack('modals')

  @livewireScripts

  @stack('scripts')
</body>

</html>

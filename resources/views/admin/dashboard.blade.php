<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                Selamat Datang, <span class="font-semibold text-purple-600">{{ Auth::user()->name }}</span>
            </h2>
            {{--<div class="text-sm text-gray-600 dark:text-gray-400">
                Selamat datang kembali, <span class="font-semibold text-purple-600">{{ Auth::user()->name }}</span>!
            </div>--}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Kartu Utama -->
            <div class="mb-8 overflow-hidden bg-gradient-to-r from-purple-600 to-pink-600 shadow-2xl rounded-3xl">
                <div class="px-8 py-12 text-center">
                    <h3 class="text-4xl font-bold text-white mb-4">Absensi Karyawan SPPG</h3>
                    <p class="text-xl text-purple-100">Dashboard Admin • {{ now()->format('d F Y') }}</p>
                </div>
            </div>

            <!-- Livewire Component -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-3xl border border-gray-200 dark:border-gray-700">
                <div class="p-8">
                    @livewire('admin.dashboard-component')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
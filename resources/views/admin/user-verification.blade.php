<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            Verifikasi Akun Karyawan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-3xl border border-gray-200 dark:border-gray-700">
                <div class="p-8">
                    @livewire('admin.user-verification')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
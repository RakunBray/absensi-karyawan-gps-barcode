<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 bg-gradient-to-b from-blue-500 to-indigo-600 rounded-full shadow-[0_0_12px_rgba(59,130,246,0.5)]"></div>
            <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">
                Dashboard Overview
            </h2>
        </div>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-10">
            <!-- Welcome Banner -->
            <div class="relative overflow-hidden rounded-[3rem] group">
                <!-- Background Accents -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-blue-500/10 rounded-full blur-[80px] group-hover:bg-blue-500/20 transition-all duration-700"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-[80px] group-hover:bg-indigo-500/20 transition-all duration-700"></div>
                
                <div class="relative bg-slate-900 border border-white/5 shadow-2xl overflow-hidden p-8 sm:p-12">
                    <!-- Overlay Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 via-transparent to-indigo-600/10 pointer-events-none"></div>
                    
                    <div class="relative flex flex-col md:flex-row items-center justify-between gap-10">
                        <div class="text-center md:text-left space-y-4 max-w-2xl">
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-widest animate-pulse">
                                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                                System Operational
                            </div>
                            <h3 class="text-4xl sm:text-5xl font-black text-white leading-[1.1] tracking-tight">
                                Selamat Datang, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">{{ Auth::user()->name }}</span>
                            </h3>
                            <p class="text-lg text-slate-400 font-medium">
                                Dashboard Admin Absensi SPPG • <span class="text-slate-300">{{ now()->format('d F Y') }}</span>
                            </p>
                        </div>

                        <div class="shrink-0 relative">
                            <div class="absolute inset-0 bg-blue-500/20 blur-3xl rounded-full"></div>
                            <div class="relative w-32 h-32 bg-white/5 border border-white/10 rounded-[2.5rem] backdrop-blur-xl flex items-center justify-center p-6 shadow-2xl">
                                <img src="/img/Logo-MBG.png" alt="Logo" class="w-full h-full object-contain filter drop-shadow-2xl">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Livewire Stats & Content Container -->
            <div class="relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600/20 to-indigo-600/20 rounded-[2.5rem] blur-xl opacity-50"></div>
                <div class="relative bg-white dark:bg-slate-900/50 backdrop-blur-3xl border border-gray-200 dark:border-white/5 shadow-3xl rounded-[2.5rem] overflow-hidden">
                    <div class="p-4 sm:p-8 lg:p-10">
                        @livewire('admin.dashboard-component')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<nav class="flex flex-col h-full bg-slate-950/80 backdrop-blur-xl border-r border-white/5 text-slate-300">
    <!-- Logo Section -->
    <div class="px-6 py-5 shrink-0">
        <a href="{{ Auth::user()->isAdmin ? route('admin.dashboard') : route('home') }}" class="flex items-center gap-3 px-4 group">
            <img src="{{asset('img/Logo-MBG.png')}}" class="w-10 h-10 object-contain hover:scale-110 transition-transform duration-300" alt="Logo">
            <div class="flex flex-col">
                <span class="text-white font-black text-xl tracking-wider leading-none group-hover:text-blue-400 transition-colors">SPPG PASEH</span>
                <span class="text-[10px] uppercase tracking-[0.2em] text-slate-500 font-bold">CIGETUR</span>
            </div>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto px-6 py-4 space-y-1 custom-scrollbar">
        @php
            $navClasses = "flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-semibold transition-all duration-300 group relative overflow-hidden";
            $activeClasses = "bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-900/20 ring-1 ring-white/20";
            $inactiveClasses = "hover:bg-white/5 hover:text-white text-slate-400";
        @endphp

        @if (Auth::user()->isAdmin)
            <div class="text-[10px] uppercase tracking-[2px] text-slate-500 font-bold px-4 mb-3 mt-2">Utama</div>
            
            <a href="{{ route('admin.dashboard') }}" class="{{ $navClasses }} {{ request()->routeIs('admin.dashboard') ? $activeClasses : $inactiveClasses }}">
                <x-heroicon-o-home class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'group-hover:text-blue-400' }}" />
                <span>{{ __('Dashboard') }}</span>
                @if(request()->routeIs('admin.dashboard'))
                    <div class="absolute right-0 w-1 y-full bg-white rounded-l-full"></div>
                @endif
            </a>

            <a href="{{ route('admin.barcodes') }}" class="{{ $navClasses }} {{ request()->routeIs('admin.barcodes') ? $activeClasses : $inactiveClasses }}">
                <x-heroicon-o-qr-code class="w-5 h-5 {{ request()->routeIs('admin.barcodes') ? 'text-white' : 'group-hover:text-blue-400' }}" />
                <span>{{ __('Barcode') }}</span>
            </a>

            <a href="{{ route('admin.attendances') }}" class="{{ $navClasses }} {{ request()->routeIs('admin.attendances') ? $activeClasses : $inactiveClasses }}">
                <x-heroicon-o-clock class="w-5 h-5 {{ request()->routeIs('admin.attendances') ? 'text-white' : 'group-hover:text-blue-400' }}" />
                <span>{{ __('Absensi') }}</span>
            </a>

            <a href="{{ route('admin.employees') }}" class="{{ $navClasses }} {{ request()->routeIs('admin.employees') ? $activeClasses : $inactiveClasses }}">
                <x-heroicon-o-users class="w-5 h-5 {{ request()->routeIs('admin.employees') ? 'text-white' : 'group-hover:text-blue-400' }}" />
                <span>{{ __('Karyawan') }}</span>
            </a>

            <a href="{{ route('admin.user-verification') }}" class="{{ $navClasses }} {{ request()->routeIs('admin.user-verification') ? $activeClasses : $inactiveClasses }}">
                <div class="relative">
                    <x-heroicon-o-shield-check class="w-5 h-5 {{ request()->routeIs('admin.user-verification') ? 'text-white' : 'group-hover:text-amber-400' }}" />
                    @php $pendingCount = \App\Models\User::where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="absolute -top-1 -right-1 flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500 shadow-sm"></span>
                        </span>
                    @endif
                </div>
                <span>{{ __('Verifikasi') }}</span>
            </a>

            <div class="pt-6">
                <div class="text-[10px] uppercase tracking-[2px] text-slate-500 font-bold px-4 mb-3">Konfigurasi</div>
                
                <!-- Master Data Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.masters.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" class="w-full {{ $navClasses }} {{ request()->routeIs('admin.masters.*') ? 'text-white bg-white/5' : 'text-slate-400 hover:bg-white/5' }}">
                        <x-heroicon-o-circle-stack class="w-5 h-5 group-hover:text-blue-400" />
                        <span class="flex-1 text-left">{{ __('Master Data') }}</span>
                        <x-heroicon-s-chevron-down class="w-4 h-4 transition-transform duration-300" x-bind:class="open ? 'rotate-180' : ''" />
                    </button>
                    <div x-show="open" x-collapse class="pl-12 space-y-1 mt-1 border-l border-white/5 ml-6">
                        @foreach(['division' => 'Divisi', 'job-title' => 'Jabatan', 'education' => 'Pendidikan', 'shift' => 'Shift', 'admin' => 'Admin'] as $key => $label)
                            <a href="{{ route('admin.masters.'.$key) }}" class="block py-2 text-sm {{ request()->routeIs('admin.masters.'.$key) ? 'text-blue-400 font-bold' : 'text-slate-500 hover:text-slate-300' }} transition-colors">
                                {{ __($label) }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Import Export Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.import-export.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" class="w-full {{ $navClasses }} {{ request()->routeIs('admin.import-export.*') ? 'text-white bg-white/5' : 'text-slate-400 hover:bg-white/5' }}">
                        <x-heroicon-o-arrows-right-left class="w-5 h-5 group-hover:text-blue-400" />
                        <span class="flex-1 text-left">{{ __('Cetak Laporan') }}</span>
                        <x-heroicon-s-chevron-down class="w-4 h-4 transition-transform duration-300" x-bind:class="open ? 'rotate-180' : ''" />
                    </button>
                    <div x-show="open" x-collapse class="pl-12 space-y-1 mt-1 border-l border-white/5 ml-6">
                        <a href="{{ route('admin.import-export.users') }}" class="block py-2 text-sm {{ request()->routeIs('admin.import-export.users') ? 'text-blue-400 font-bold' : 'text-slate-500 hover:text-slate-300' }}">Karyawan/Admin</a>
                        <a href="{{ route('admin.import-export.attendances') }}" class="block py-2 text-sm {{ request()->routeIs('admin.import-export.attendances') ? 'text-blue-400 font-bold' : 'text-slate-500 hover:text-slate-300' }}">Absensi</a>
                    </div>
                </div>
            </div>
        @else
            <a href="{{ route('home') }}" class="{{ $navClasses }} {{ request()->routeIs('home') ? $activeClasses : $inactiveClasses }}">
                <x-heroicon-o-home class="w-5 h-5" />
                <span>{{ __('Beranda') }}</span>
            </a>
        @endif
    </div>

    <!-- User Profile Section -->
    <div class="px-6 py-6 border-t border-white/5 bg-white/[0.02]">
        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('profile.show') }}" class="flex items-center gap-3 group min-w-0 flex-1">
                <div class="relative shrink-0">
                    <img class="h-10 w-10 rounded-xl object-cover ring-2 ring-white/10 group-hover:ring-blue-500/50 transition-all duration-300" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-2 border-slate-950 rounded-full shadow-sm"></div>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-xs font-bold text-white group-hover:text-blue-400 transition truncate">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] text-slate-500 group-hover:text-slate-400 truncate uppercase tracking-widest">{{ Auth::user()->group }}</span>
                </div>
            </a>

            <div class="flex items-center gap-1">
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <button type="submit" @click.prevent="$root.submit();" class="p-2 text-slate-500 hover:text-red-500 hover:bg-red-500/10 rounded-xl transition-all" title="Logout">
                        <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5" />
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.05); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.1); }
</style>

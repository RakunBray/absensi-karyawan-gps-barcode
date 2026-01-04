<div class="space-y-6 p-2">
    
    {{-- BAGIAN 1: KONFIGURASI FILTER & EKSPOR --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        
        {{-- Header Card --}}
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex items-center gap-3">
            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Ekspor Data Absensi</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Filter data sebelum diunduh.</p>
            </div>
        </div>

        <form wire:submit.prevent="export">
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    {{-- KOLOM KIRI: PENGATURAN WAKTU --}}
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                1. Pilih Periode
                            </label>
                        </div>

                        {{-- Toggle Button --}}
                        <div class="flex bg-gray-100 dark:bg-gray-900 p-1 rounded-lg w-full max-w-sm">
                            <button type="button" wire:click="$set('periodType', 'range')"
                                class="flex-1 text-xs py-2 px-4 rounded-md font-semibold transition-all duration-200 {{ $periodType === 'range' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                                Custom Range
                            </button>
                            <button type="button" wire:click="$set('periodType', 'monthly')"
                                class="flex-1 text-xs py-2 px-4 rounded-md font-semibold transition-all duration-200 {{ $periodType === 'monthly' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                                Bulanan
                            </button>
                        </div>

                        {{-- Input Tanggal --}}
                        <div>
                            @if($periodType === 'range')
                                <div class="flex items-center gap-2">
                                    <div class="w-full">
                                        <label class="block text-[10px] text-gray-400 mb-1">Dari Tanggal</label>
                                        <input type="date" wire:model.live="startDate" 
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2">
                                    </div>
                                    <span class="text-gray-400 mt-5">-</span>
                                    <div class="w-full">
                                        <label class="block text-[10px] text-gray-400 mb-1">Sampai Tanggal</label>
                                        <input type="date" wire:model.live="endDate" 
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2">
                                    </div>
                                </div>
                                <p class="text-[10px] text-indigo-500 dark:text-indigo-400 mt-1 italic">*Tips: Pilih Senin - Minggu untuk laporan mingguan.</p>
                            @else
                                <div>
                                    <label class="block text-[10px] text-gray-400 mb-1">Pilih Bulan</label>
                                    <input type="month" wire:model.live="selectedMonth" 
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2">
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- KOLOM KANAN: FILTER TAMBAHAN --}}
                    <div class="space-y-5">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            2. Filter Karyawan
                        </label>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] text-gray-400 mb-1">Divisi</label>
                                <select wire:model.live="division" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2">
                                    <option value="">Semua Divisi</option>
                                    @foreach (App\Models\Division::all() as $div)
                                        <option value="{{ $div->id }}">{{ $div->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-400 mb-1">Jabatan</label>
                                <select wire:model.live="job_title" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2">
                                    <option value="">Semua Jabatan</option>
                                    @foreach (App\Models\JobTitle::all() as $job)
                                        <option value="{{ $job->id }}">{{ $job->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOTER CARD: TOMBOL AKSI --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                <button type="submit" wire:loading.attr="disabled" 
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-6 rounded-lg shadow transition transform hover:scale-[1.02]">
                    
                    {{-- Icon Default --}}
                    <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    
                    {{-- Icon Loading --}}
                    <svg wire:loading class="animate-spin -ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    
                    <span wire:loading.remove>Download Excel</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </div>
        </form>
    </div>

    {{-- BAGIAN 2: HASIL PRATINJAU --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col">
        
        {{-- Header Tabel --}}
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Hasil Pratinjau</h3>
                <p class="text-xs text-gray-500 mt-1">
                    Periode: 
                    @if($periodType == 'range')
                        <span class="font-medium text-indigo-500">{{ \Carbon\Carbon::parse($startDate)->format('d M') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
                    @else
                        <span class="font-medium text-indigo-500">{{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }}</span>
                    @endif
                </p>
            </div>
            <div class="text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1.5 rounded-full border border-gray-200 dark:border-gray-600">
                Total: {{ $attendances->total() }} Data
            </div>
        </div>

        {{-- Tabel --}}
        <div class="w-full overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Karyawan</th>
                        <th class="px-6 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Divisi</th>
                        <th class="px-6 py-3 text-center text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Masuk</th>
                        <th class="px-6 py-3 text-center text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jam Pulang</th>
                        <th class="px-6 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($attendances as $attendance)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                            <td class="px-6 py-3 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400 font-mono">
                                {{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="text-xs font-bold text-gray-800 dark:text-white">{{ $attendance->user->name ?? '-' }}</div>
                                <div class="text-[10px] text-gray-500 dark:text-gray-500">{{ $attendance->user->position->name ?? 'Staff' }}</div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                {{ $attendance->user->division->name ?? '-' }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center text-xs text-emerald-600 dark:text-emerald-400 font-bold">
                                {{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('H:i') : '-' }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center text-xs text-red-600 dark:text-red-400 font-bold">
                                {{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('H:i') : '-' }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                @php
                                    $status = strtolower($attendance->status);
                                    $color = match(true) {
                                        in_array($status, ['hadir', 'present']) => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800',
                                        in_array($status, ['sakit', 'sick']) => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
                                        in_array($status, ['izin', 'permit', 'excused']) => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800',
                                        in_array($status, ['telat', 'late']) => 'bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-300 border border-orange-200 dark:border-orange-800',
                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 inline-flex text-[10px] leading-4 font-bold rounded-full {{ $color }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-10 h-10 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-sm font-medium">Tidak ada data ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Pagination --}}
        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 text-xs">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
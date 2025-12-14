{{-- resources/views/components/validation-errors.blade.php --}}
@if ($errors->any())
    <div class="mb-8">
        <div class="p-5 bg-red-900/70 border border-red-500/50 rounded-2xl backdrop-blur-xl shadow-lg">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-300 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-2.964 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="text-red-200 text-sm">
                    <p class="font-bold mb-2">Oops! Ada yang salah:</p>
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <span class="text-red-400">•</span> {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
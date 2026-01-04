{{-- resources/views/auth/reset-password.blade.php --}}
@extends('layouts.guest-register')

@section('content')
<div class="w-full max-w-md mx-auto">
    <!-- Logo & Header -->
    <div class="flex flex-col items-center mb-6">
        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center p-3 shadow-xl shadow-blue-500/20 ring-1 ring-white/20 mb-4 transition hover:rotate-3">
            <img src="/img/Logo-MBG.png" alt="Logo" class="w-full h-full object-contain">
        </div>
        <h2 class="text-3xl font-bold tracking-tight text-white text-center">Kata Sandi Baru</h2>
        <p class="text-slate-400 text-sm mt-1 text-center">Masukkan kata sandi baru yang kuat untuk akun Anda.</p>
    </div>

    <!-- Main Card -->
    <div class="backdrop-blur-xl bg-white/5 border border-white/10 rounded-[2.5rem] shadow-2xl p-8 sm:p-10">
        <x-validation-errors class="mb-6 text-red-400 text-sm bg-red-500/10 p-3 rounded-xl border border-red-500/20" />

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email (readonly) -->
            <div class="space-y-1.5 focus-within:z-10 opacity-70">
                <label for="email" class="text-[10px] font-bold text-slate-500 ml-2 uppercase tracking-widest">Email</label>
                <x-input type="text" name="email" id="email" :value="old('email', $request->email)" required readonly
                    class="w-full pl-5 pr-5 py-3 bg-slate-900/40 border border-white/5 rounded-xl text-white/60 text-sm cursor-not-allowed" />
            </div>

            <!-- Kata Sandi Baru -->
            <div class="space-y-1.5 focus-within:z-10">
                <label for="password" class="text-[10px] font-bold text-slate-500 ml-2 uppercase tracking-widest">Kata Sandi Baru</label>
                <div class="relative">
                    <x-input type="password" name="password" id="password" required minlength="8" autocomplete="new-password"
                        class="w-full pl-5 pr-12 py-3 bg-slate-900/50 border {{ $errors->has('password') ? 'border-red-500/50' : 'border-white/10' }} rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:bg-slate-900 transition-all text-sm"
                        placeholder="Minimal 8 karakter" />
                    <button type="button" onclick="togglePass('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors p-1.5">
                        <svg class="w-4 h-4" id="eye-icon-password" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </button>
                </div>
            </div>

            <!-- Konfirmasi -->
            <div class="space-y-1.5 focus-within:z-10">
                <label for="password_confirmation" class="text-[10px] font-bold text-slate-500 ml-2 uppercase tracking-widest">Konfirmasi Sandi</label>
                <div class="relative">
                    <x-input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full pl-5 pr-12 py-3 bg-slate-900/50 border border-white/10 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:bg-slate-900 transition-all text-sm"
                        placeholder="Ulangi kata sandi" />
                    <button type="button" onclick="togglePass('password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors p-1.5">
                        <svg class="w-4 h-4" id="eye-icon-password_confirmation" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-900/20 transition-all hover:scale-[1.02] active:scale-[0.98] outline-none ring-offset-2 ring-offset-slate-950 focus:ring-2 focus:ring-blue-500 mt-2">
                Simpan Kata Sandi
            </button>
        </form>
    </div>
</div>

<script>
function togglePass(fieldId, button) {
    const input = document.getElementById(fieldId);
    const icon = button.querySelector('svg');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
    } else {
        input.type = 'password';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}
</script>
@endsection

    <script>
        function togglePass(fieldId, button) {
            const input = document.getElementById(fieldId);
            const eyeOpen = button.querySelector(`#eye-open-${fieldId}`);
            const eyeClosed = button.querySelector(`#eye-closed-${fieldId}`);
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        }
    </script>
@endsection
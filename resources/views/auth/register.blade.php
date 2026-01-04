{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.guest-register')

@section('content')
<div class="w-full max-w-4xl mx-auto py-4">
    <!-- Logo & Header -->
    <div class="flex flex-col items-center mb-4">
        <img src="/img/Logo-MBG.png" alt="Logo" class="h-16 w-auto mb-2 object-contain hover:scale-105 transition-transform">
        <h2 class="text-2xl font-bold tracking-tight text-white text-center">Daftar Akun Baru</h2>
        <p class="text-slate-400 text-sm">Buat akun Absensi SPPG Anda</p>
    </div>

    <!-- Main Card -->
    <div class="backdrop-blur-xl bg-white/5 border border-white/10 rounded-[2rem] shadow-2xl p-6">
        <x-validation-errors class="mb-4 text-red-400 text-sm bg-red-500/10 p-2 rounded-lg border border-red-500/20" />

        <form method="POST" action="{{ route('register') }}" class="space-y-3">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">
                <!-- Name -->
                <div class="space-y-1 focus-within:z-10">
                    <label for="name" class="text-[10px] font-bold text-slate-500 ml-2 uppercase tracking-widest">Nama Lengkap</label>
                    <x-input type="text" name="name" id="name" :value="old('name')" required autofocus 
                        class="w-full pl-5 pr-5 py-2.5 bg-slate-900/50 border {{ $errors->has('name') ? 'border-red-500/50' : 'border-white/10' }} rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:bg-slate-900 transition-all text-sm"
                        placeholder="Masukkan nama lengkap" />
                </div>

                <!-- Email -->
                <div class="space-y-1 focus-within:z-10">
                    <label for="email" class="text-[10px] font-bold text-slate-500 ml-2 uppercase tracking-widest">Email</label>
                    <x-input type="email" name="email" id="email" :value="old('email')" required
                        class="w-full pl-5 pr-5 py-2.5 bg-slate-900/50 border {{ $errors->has('email') ? 'border-red-500/50' : 'border-white/10' }} rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:bg-slate-900 transition-all text-sm"
                        placeholder="email@contoh.com" />
                </div>

                <!-- NIP -->
                <div class="space-y-1 focus-within:z-10">
                    <label for="nip" class="text-[10px] font-bold text-slate-500 ml-2 uppercase tracking-widest">NIP</label>
                    <x-input type="text" name="nip" id="nip" :value="old('nip')" required inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="w-full pl-5 pr-5 py-2.5 bg-slate-900/50 border {{ $errors->has('nip') ? 'border-red-500/50' : 'border-white/10' }} rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:bg-slate-900 transition-all text-sm"
                        placeholder="Nomor Induk Pegawai" />
                </div>

                <!-- Gender -->
                <div class="space-y-1 focus-within:z-10">
                    <label for="gender" class="text-[10px] font-bold text-slate-500 ml-2 uppercase tracking-widest">Jenis Kelamin</label>
                    <select name="gender" id="gender" required
                        class="w-full pl-5 pr-5 py-2.5 bg-slate-900/50 border {{ $errors->has('gender') ? 'border-red-500/50' : 'border-white/10' }} rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:bg-slate-900 transition-all text-sm appearance-none">
                        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <!-- Phone -->
                <div class="space-y-1 focus-within:z-10">
                    <label for="phone" class="text-[10px] font-bold text-slate-500 ml-2 uppercase tracking-widest">Nomor Telepon</label>
                    <x-input type="text" name="phone" id="phone" :value="old('phone')" required inputmode="numeric" pattern="08[0-9]{8,11}" maxlength="13" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0?/, '0').substring(0,13)"
                        class="w-full pl-5 pr-5 py-2.5 bg-slate-900/50 border {{ $errors->has('phone') ? 'border-red-500/50' : 'border-white/10' }} rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:bg-slate-900 transition-all text-sm"
                        placeholder="08xxxxxxxxxx" />
                </div>

                <!-- City -->
                <div class="space-y-1 focus-within:z-10">
                    <label for="city" class="text-[10px] font-bold text-slate-500 ml-2 uppercase tracking-widest">Kota</label>
                    <x-input type="text" name="city" id="city" :value="old('city')" required
                        class="w-full pl-5 pr-5 py-2.5 bg-slate-900/50 border {{ $errors->has('city') ? 'border-red-500/50' : 'border-white/10' }} rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:bg-slate-900 transition-all text-sm"
                        placeholder="Kota asal" />
                </div>

                <!-- Address -->
                <div class="md:col-span-2 space-y-1 focus-within:z-10">
                    <label for="address" class="text-[10px] font-bold text-slate-500 ml-2 uppercase tracking-widest">Alamat Lengkap</label>
                    <textarea name="address" id="address" required rows="2"
                        class="w-full pl-5 pr-5 py-2.5 bg-slate-900/50 border {{ $errors->has('address') ? 'border-red-500/50' : 'border-white/10' }} rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:bg-slate-900 transition-all text-sm resize-none"
                        placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                </div>

                <!-- Password -->
                <div class="space-y-1 focus-within:z-10">
                    <label for="password" class="text-[10px] font-bold text-slate-500 ml-2 uppercase tracking-widest">Kata Sandi</label>
                    <div class="relative">
                        <x-input type="password" name="password" id="password" required autocomplete="new-password" minlength="8"
                            class="w-full pl-5 pr-12 py-2.5 bg-slate-900/50 border {{ $errors->has('password') ? 'border-red-500/50' : 'border-white/10' }} rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:bg-slate-900 transition-all text-sm"
                            placeholder="min. 8 karakter" />
                        <button type="button" onclick="togglePass('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors p-1.5">
                            <svg class="w-4 h-4" id="eye-icon-password" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </button>
                    </div>
                </div>

                <!-- Password Confirmation -->
                <div class="space-y-1 focus-within:z-10">
                    <label for="password_confirmation" class="text-[10px] font-bold text-slate-500 ml-2 uppercase tracking-widest">Konfirmasi Sandi</label>
                    <div class="relative">
                        <x-input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full pl-5 pr-12 py-2.5 bg-slate-900/50 border border-white/10 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:bg-slate-900 transition-all text-sm"
                            placeholder="Ulangi kata sandi" />
                        <button type="button" onclick="togglePass('password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors p-1.5">
                            <svg class="w-4 h-4" id="eye-icon-password_confirmation" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-900/20 transition-all hover:scale-[1.01] active:scale-[0.99] outline-none ring-offset-2 ring-offset-slate-950 focus:ring-2 focus:ring-blue-500 mt-4">
                Daftar Sekarang
            </button>

            <div class="pt-1 text-center">
                <p class="text-slate-500 text-xs">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-blue-400 font-bold hover:text-blue-300 transition-colors">Masuk di sini</a>
                </p>
            </div>
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
{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.guest-register')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-4xl">

            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-500 rounded-3xl flex items-center justify-center text-5xl font-bold text-white shadow-2xl ring-8 ring-purple-500/30">
                    M
                </div>
            </div>

            <!-- CARD RAPAT & SIMETRIS -->
            <div class="backdrop-blur-3xl bg-white/10 border border-white/30 rounded-3xl shadow-3xl p-8">

                <h2 class="text-3xl font-bold text-white text-center mb-2">Daftar Akun Baru</h2>
                <p class="text-center text-purple-200 text-sm mb-8">Buat akun Absensi MBG Anda</p>

                <x-validation-errors class="mb-6 text-red-400 text-center text-sm" />

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- GRID 2 KOLOM — SEMUA SEJAJAR -->
                    <div class="grid md:grid-cols-2 gap-5">

                        <!-- Kiri -->
                        <div class="space-y-5">
                            <x-input type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                                class="w-full px-6 py-4 bg-black/60 border {{ $errors->has('name') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/60 focus:border-purple-400 transition text-base shadow-lg"
                                placeholder="Nama Lengkap" />
                            @error('name') <p class="mt-1 text-red-400 text-xs">{{ $message }}</p> @enderror

                            <x-input type="text" name="nip" :value="old('nip')" required inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full px-6 py-4 bg-black/60 border {{ $errors->has('nip') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/60 focus:border-purple-400 transition text-base shadow-lg"
                                placeholder="NIP" />
                            @error('nip') <p class="mt-1 text-red-400 text-xs">{{ $message }}</p> @enderror

                            <x-input type="text" name="phone" :value="old('phone')" required inputmode="numeric"
                                pattern="08[0-9]{8,11}" maxlength="13"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0?/, '0').substring(0,13)"
                                class="w-full px-6 py-4 bg-black/60 border {{ $errors->has('phone') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/60 focus:border-purple-400 transition text-base shadow-lg"
                                placeholder="08xxxxxxxxxx" />
                            @error('phone') <p class="mt-1 text-red-400 text-xs">{{ $message }}</p> @enderror

                            <x-input type="text" name="city" :value="old('city')" required
                                class="w-full px-6 py-4 bg-black/60 border {{ $errors->has('city') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/60 focus:border-purple-400 transition text-base shadow-lg"
                                placeholder="Kota" />
                            @error('city') <p class="mt-1 text-red-400 text-xs">{{ $message }}</p> @enderror

                            <!-- KATA SANDI — DI KIRI -->
                            <div class="relative">
                                <x-input type="password" name="password" id="password" required autocomplete="new-password" minlength="8"
                                    class="w-full px-6 py-4 pr-14 bg-black/60 border {{ $errors->has('password') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/60 focus:border-purple-400 transition text-base shadow-lg"
                                    placeholder="Kata Sandi (min. 8 karakter)" />
                                <button type="button" onclick="togglePass('password', this)"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 hover:text-white">
                                    <svg class="w-6 h-6 hidden" id="eye-open-password" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg class="w-6 h-6" id="eye-closed-password" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                </button>
                                @error('password') <p class="mt-1 text-red-400 text-xs">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Kanan -->
                        <div class="space-y-5">
                            <x-input type="email" name="email" :value="old('email')" required
                                class="w-full px-6 py-4 bg-black/60 border {{ $errors->has('email') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/60 focus:border-purple-400 transition text-base shadow-lg"
                                placeholder="Email" />
                            @error('email') <p class="mt-1 text-red-400 text-xs">{{ $message }}</p> @enderror

                            <x-select name="gender" required
                                class="w-full px-6 py-4 bg-black/60 border {{ $errors->has('gender') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white focus:outline-none focus:ring-4 focus:ring-purple-500/60 focus:border-purple-400 transition text-base shadow-lg">
                                <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Jenis Kelamin</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                            </x-select>
                            @error('gender') <p class="mt-1 text-red-400 text-xs">{{ $message }}</p> @enderror

                            <x-textarea name="address" :value="old('address')" required rows="4"
                                class="w-full px-6 py-4 bg-black/60 border {{ $errors->has('address') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/60 focus:border-purple-400 transition text-base resize-none shadow-lg"
                                placeholder="Alamat Lengkap"></x-textarea>
                            @error('address') <p class="mt-1 text-red-400 text-xs">{{ $message }}</p> @enderror

                            <!-- KONFIRMASI KATA SANDI — DI KANAN -->
                            <div class="relative">
                                <x-input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="w-full px-6 py-4 pr-14 bg-black/60 border {{ $errors->has('password') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/60 focus:border-purple-400 transition text-base shadow-lg"
                                    placeholder="Konfirmasi Kata Sandi" />
                                <button type="button" onclick="togglePass('password_confirmation', this)"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 hover:text-white">
                                    <svg class="w-6 h-6 hidden" id="eye-open-password_confirmation" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg class="w-6 h-6" id="eye-closed-password_confirmation" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol & Link -->
                    <div class="mt-10 text-center space-y-6">
                        <button type="submit"
                            class="w-full max-w-md mx-auto py-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-lg font-bold rounded-full shadow-2xl transition hover:scale-105">
                            Daftar Sekarang
                        </button>

                        <p class="text-gray-400 text-sm">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="text-purple-300 font-semibold hover:text-white underline">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Toggle Password -->
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
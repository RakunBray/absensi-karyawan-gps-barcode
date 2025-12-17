<x-guest-login>
    <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="flex justify-center mb-10">
            <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-pink-500 rounded-3xl flex items-center justify-center text-5xl font-bold text-white shadow-2xl ring-8 ring-purple-500/30">
                <img src="#"= alt="logo">
            </div>
        </div>

        <!-- Glass Card -->
        <div class="backdrop-blur-2xl bg-white/10 border border-white/20 rounded-3xl shadow-2xl p-10">
            <h2 class="text-3xl font-bold text-white text-center mb-8">Selamat Datang Kembali</h2>

            <x-validation-errors class="mb-6 text-red-400 text-center" />

            @if (session('status'))
                <div class="mb-6 text-green-400 text-center font-medium">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-6">
                    <x-input type="text" name="email" :value="old('email')" required autofocus autocomplete="username"
                        class="w-full px-6 py-4 bg-white/10 border border-white/30 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 transition text-lg"
                        placeholder="Email atau Nomor Telepon" />
                </div>

                <div class="mb-6">
                    <x-input type="password" name="password" required autocomplete="current-password"
                        class="w-full px-6 py-4 bg-white/10 border border-white/30 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 transition text-lg"
                        placeholder="Kata Sandi" />
                </div>

                <div class="flex items-center justify-between mb-8 text-sm">
                    <label class="flex items-center text-gray-300">
                        <x-checkbox name="remember" class="rounded border-white/50 text-purple-500 focus:ring-purple-500" />
                        <span class="ml-3">Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-cyan-400 hover:text-cyan-300">
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full py-5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-xl font-bold rounded-2xl shadow-xl transform transition hover:scale-105">
                    Masuk Sekarang
                </button>

                @if (Route::has('register'))
                    <p class="text-center mt-8 text-gray-400">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-purple-400 font-bold hover:text-purple-300">Daftar di sini</a>
                    </p>
                @endif
            </form>

            <p class="text-center text-gray-500 text-xs mt-10">
                © {{ date('Y') }} SPPG Corporation • Dibuat oleh <span class="text-purple-400 font-bold">kamu</span>
            </p>
        </div>
    </div>
</x-guest-login>
<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /**
         * Redirect setelah login berdasarkan role
         */
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                $user = Auth::user();

                if ($user && in_array($user->group, ['admin', 'superadmin'])) {
                    return redirect('/admin/dashboard');
                }

                return redirect('/home');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        /**
         * Custom authentication:
         * - Login pakai email atau nomor HP
         * - Blok akun nonaktif (disabled)
         */
        Fortify::authenticateUsing(function (Request $request) {
            $login = $request->input('email');
            if (! $login) {
                return null;
            }

            $user = User::where('email', $login)
                        ->orWhere('phone', $login)
                        ->first();

            if (! $user) {
                return null;
            }

            if (! Hash::check($request->password, $user->password)) {
                return null;
            }

            // 🔒 Cek Status Approval & Aktif
            if ($user->status !== 'approved') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => ['Akun Anda belum diverifikasi atau sedang menunggu persetujuan admin.'],
                ]);
            }

            return $user;
        });

        // ==============================================
        // TAMBAHAN UNTUK EMAIL VERIFICATION
        // ==============================================

        // View yang ditampilkan saat user login tapi email belum diverifikasi
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email'); // pastikan file ini ada di resources/views/auth/verify-email.blade.php
        });

        // Opsional: Custom view untuk halaman "Kirim Ulang Verifikasi" (jika ingin terpisah)
        // Fortify::requestVerificationLinkView(function () {
        //     return view('auth.forgot-verification');
        // });

        // Opsional: Custom view untuk konfirmasi bahwa link verifikasi sudah dikirim ulang
        // Fortify::confirmVerificationLinkView(function () {
        //     return view('auth.link-sent');
        // });
    }
}
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
        // REDIRECT SETELAH LOGIN — 100% PASTI JALAN
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                $user = Auth::user();

                // CEK KOLOM 'group' LANGSUNG — TIDAK PAKAI METHOD, TIDAK BISA GAGAL!
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

        // CUSTOM LOGIN — BISA PAKAI EMAIL ATAU NOMOR TELEPON
        // PASTIKAN DI FORM KAMU PAKAI name="login"
        Fortify::authenticateUsing(function (Request $request) {
            $login = $request->input('login'); // <-- nama field di form harus "login"

            if (!$login) {
                return null;
            }

            $user = User::where('email', $login)
                        ->orWhere('phone', $login)
                        ->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            return null;
        });
    }
}
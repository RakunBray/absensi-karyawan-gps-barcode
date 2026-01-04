<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticateLoginAttempt
{
    public function __invoke(Request $request)
    {
        $login = $request->input('email');

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $login)->first();
        } else {
            $user = User::where('phone', $login)->first();
        }

        if ($user && Hash::check($request->password, $user->password)) {
            // Admin & Superadmin bypass approval check
            if (in_array($user->group, ['admin', 'superadmin'])) {
                return $user;
            }

            if ($user->status === 'pending') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => ['Akun Anda sedang menunggu persetujuan admin.'],
                ]);
            }

            if ($user->status === 'rejected') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => ['Akun Anda telah ditolak. Silakan hubungi admin.'],
                ]);
            }

            if ($user->group === 'disabled') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => ['Akun Anda telah dinonaktifkan.'],
                ]);
            }

            return $user;
        }

        return null;
    }
}

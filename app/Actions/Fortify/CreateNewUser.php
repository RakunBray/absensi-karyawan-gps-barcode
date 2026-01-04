<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:10', 'unique:users,nip'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'regex:/^08[0-9]{8,11}$/', 'unique:users,phone'],
            'gender' => ['required', 'in:male,female'],
            'city' => ['required'],
            'address' => ['required'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature()
                ? ['accepted', 'required']
                : '',
        ])->validate();

        return User::create([
            'name'              => $input['name'],
            'nip'               => $input['nip'],
            'email'             => $input['email'],
            'phone'             => $input['phone'],
            'gender'            => $input['gender'],
            'city'              => $input['city'],
            'address'           => $input['address'],
            'password'          => Hash::make($input['password']),
            'group'             => 'user',
            'status'            => 'pending',
            'email_verified_at' => null, // 🔒 BELUM AKTIF
        ]);
    }
}

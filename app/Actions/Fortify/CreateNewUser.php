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

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:50', 'unique:users,nip'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^08[0-9]{8,11}$/', 'unique:users,phone'],
            'gender' => ['required', 'in:male,female'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:500'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            // Custom pesan error (biar lebih ramah)
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'phone.regex' => 'Nomor telepon harus dimulai dengan 08 dan berisi 10-13 angka.',
            'phone.unique' => 'Nomor telepon sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
            'gender.in' => 'Jenis kelamin tidak valid.',
            'terms.accepted' => 'Anda harus menyetujui Syarat & Ketentuan.',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'nip' => $input['nip'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'gender' => $input['gender'],
            'city' => $input['city'],
            'address' => $input['address'],
            'password' => Hash::make($input['password']),
            'raw_password' => $input['password'], // Untuk debug / laporan (opsional, bisa dihapus nanti)
        ]);
    }
}
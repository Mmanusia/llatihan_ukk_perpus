<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validasi input dan membuat pengguna baru.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        // Mengambil data input dan melakukan validasi
        Validator::make($input, [
            'username' => ['required', 'string', 'max:255'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();
        
        // Membuat user baru
        return User::create([
            'username' => $input['username'],
            'nama_lengkap' => $input['nama_lengkap'],
            'alamat' => $input['alamat'],
            'role' => 'peminjam',
            'email' => $input['email'],
            'password' => Hash::make($input['password']),   // Hash password sebelum disimpan
        ]);
    }
}

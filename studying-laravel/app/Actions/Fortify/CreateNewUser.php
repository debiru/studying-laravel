<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Enums\Users\{Gender, Role, Status};

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
        $rules = [
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password' => $this->passwordRules(),
            'password_confirmation' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'name_kana' => ['required', 'string', 'max:255'],
        ];

        $messages = [];

        Validator::make($input, $rules, $messages)->validate();

        return User::create([
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'name' => $input['name'],
            'name_kana' => $input['name_kana'],
            'address_zipcode' => '',
            'address' => '',
            'phone_number' => '',
        ]);
    }
}

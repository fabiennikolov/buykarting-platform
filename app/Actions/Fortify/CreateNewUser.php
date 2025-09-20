<?php

namespace App\Actions\Fortify;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', 'string', 'in:individual,business'],
            'country' => ['required', 'string', 'max:100'],
            'state_province' => ['nullable', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'type' => $input['type'],
            'country' => $input['country'],
            'state_province' => $input['state_province'] ?? null,
            'city' => $input['city'],
        ]);

        // Create freemium subscription for new user
        Subscription::createFreemium($user);

        return $user;
    }
}

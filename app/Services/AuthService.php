<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(array $data): array|bool
    {
        $credentials = Arr::only($data, ['phone', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return false;
        }

        $user = Auth::user();

        return [
            'token' => $token,
            'user' => $user
        ];
    }

    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $token = Auth::login($user);

        return [
            'token' => $token,
            'user' => $user
        ];
    }
}

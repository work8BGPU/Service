<?php

namespace App\Services;

use App\Models\Phone;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(protected UserService $userService)
    {
    }

    public function login(array $data): array|bool
    {
        $phone = $data['phone'];

        $phoneModel = Phone::where('phone', $phone)->first();

        if (!$phoneModel) {
            return false;
        }

        $credentials = [
            'phone_id' => $phoneModel->id,
            'password' => $data['password'],
        ];

        if (!$token = Auth::attempt($credentials)) {
            return false;
        }

        $user = $this->userService->getMe();
        
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

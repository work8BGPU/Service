<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Неправильный логин или пароль'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $data['token'];
        $user = $data['user'];

        return response()->json([
            'status' => 'success',
            'message' => 'Вход выполнен',
            'user' => $user,
            'token' => $token
        ], Response::HTTP_CREATED);
    }

    public function register(RegisterRequest $request)
    {
        $data = $this->authService->register($request->validated());

        $token = $data['token'];
        $user = $data['user'];

        return response()->json([
            'status' => 'success',
            'message' => 'Регистрация выполнена',
            'user' => $user,
            'token' => $token
        ], Response::HTTP_CREATED);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Выход выполнен'
        ]);
    }

    public function refresh()
    {
        //TODO: При обновлении токена, если access token будет не тот, то выдать ошибку
        return response()->json([
            'status' => 'success',
            'message' => 'Токен обновлен',
            'user' => Auth::user(),
            'token' => Auth::refresh()
        ]);
    }
}

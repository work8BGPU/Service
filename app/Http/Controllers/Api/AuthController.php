<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User\User;
use App\Services\AuthService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService, protected UserService $userService)
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
        try {
            $token = JWTAuth::getToken();
            if (!$token) {
                return response()->json(['message' => 'Токен не предоставлен'], Response::HTTP_BAD_REQUEST);
            }

            $newToken = Auth::refresh();

            return response()->json([
                'status' => 'success',
                'message' => 'Токен обновлен',
                'token' => $newToken
            ]);
        } catch (TokenExpiredException $e) {
            return response()->json(['message' => 'Токен просрочен', 'token' => Auth::refresh()], Response::HTTP_UNAUTHORIZED);
        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Неверный токен'], Response::HTTP_UNAUTHORIZED);
        } catch (Exception $e) {
            return response()->json(['message' => 'Ошибка обновления токена'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function me()
    {
        $user = $this->userService->getMe();

        if (!$user) {
            return response()->json([
                'message' => 'unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'status' => 'success',
            'user' => $user
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
        
    }

    public function createData()
    {
        $data = $this->userService->createData();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $employee = $this->userService->create($data);

        if ($employee) {
            return response()->json([
                'status' => 'success',
                'message' => 'Пользователь создан'
            ], Response::HTTP_CREATED);
        }
    }
}

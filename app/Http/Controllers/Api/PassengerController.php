<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Passenger\StoreRequest;
use App\Services\PassengerService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PassengerController extends Controller
{
    public function __construct(protected PassengerService $passengerService)
    {
    }

    public function createData()
    {
        $data = $this->passengerService->createData();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $employee = $this->passengerService->create($data);

        if ($employee) {
            return response()->json([
                'status' => 'success',
                'message' => 'Сотрудник создан'
            ], Response::HTTP_CREATED);
        }
    }
}

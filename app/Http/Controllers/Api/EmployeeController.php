<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Employee\StoreRequest;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $employeeService)
    {
    }

    public function createData()
    {
        $data = $this->employeeService->createData();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $employee = $this->employeeService->create($data);

        if ($employee) {
            return response()->json([
                'status' => 'success',
                'message' => 'Сотрудник создан'
            ], Response::HTTP_CREATED);
        }
    }
}

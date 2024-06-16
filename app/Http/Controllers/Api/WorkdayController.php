<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Workday\StoreRequest;
use App\Services\WorkdayService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WorkdayController extends Controller
{
    public function __construct(protected WorkdayService $workdayService)
    {
    }

    public function createData()
    {
        $data = $this->workdayService->createData();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $employee = $this->workdayService->create($data);

        if ($employee) {
            return response()->json([
                'status' => 'success',
                'message' => 'Рабочий день создан'
            ], Response::HTTP_CREATED);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Request\StoreRequest;
use App\Http\Requests\Api\Request\UpdateStatusRequest;
use App\Http\Resources\RequestResource;
use App\Models\Request\Request;
use App\Services\RequestService;
use Illuminate\Http\Response;

class RequestController extends Controller
{
    public function __construct(protected RequestService $requestService)
    {
    }

    public function index()
    {
        $requests = Request::with(['passenger', 'stationDeparture', 'stationArrival', 'category', 'status'])->paginate(20);
        return RequestResource::collection($requests);
    }

    public function updateStatus(UpdateStatusRequest $requestHttp, Request $request)
    {
        $data = $requestHttp->validated();
        $this->requestService->update($request, $data);
        return response()->json();
    }

    public function createData()
    {
        $data = $this->requestService->createData();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $employee = $this->requestService->create($data);

        if ($employee) {
            return response()->json([
                'status' => 'success',
                'message' => 'Заявка создана'
            ], Response::HTTP_CREATED);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MetroStation\FindBestRoutes;
use App\Models\Station\MetroStation;
use App\Services\MetroStationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MetroStationController extends Controller
{
    public function __construct(protected MetroStationService $metroStationService)
    {
    }

    public function findBestRoutes(FindBestRoutes $request)
    {
        $data = $request->validated();
        $paths = $this->metroStationService->findBestPaths($data['station_departure']['id'], $data['station_arrival']['id']); 
        if (!$paths) {
            return response()->json(['status' => 'error'], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => 'success',
            'data' => $paths
        ]);
    }
}

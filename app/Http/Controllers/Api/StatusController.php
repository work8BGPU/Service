<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StatusResource;
use App\Models\Request\RequestStatus;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index() {
        $statuses = RequestStatus::all();
        return StatusResource::collection($statuses);
    }
}

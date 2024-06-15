<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Phone;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    public function show(Phone $phone)
    {
        return response()->json([
            'phone' => $phone
        ]);
    }
}

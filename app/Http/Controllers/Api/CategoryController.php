<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Passenger\CategoryPassenger;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(CategoryPassenger $category)
    {
        return response()->json([
            'category' => $category
        ]);
    }
}

<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\MetroStationController;
use App\Http\Controllers\Api\PassengerController;
use App\Http\Controllers\Api\PhoneController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WorkdayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('register', [AuthController::class, 'register'])->name('register');
    });

    Route::middleware('auth:api')->group(function () {
        Route::post('me', [AuthController::class, 'me'])->name('me');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::post('employees', [EmployeeController::class, 'store'])->name('employee.store');
        Route::get('employees/createData', [EmployeeController::class, 'createData'])->name('employee.createData');

        Route::post('workdays', [WorkdayController::class, 'store'])->name('workday.store');
        Route::get('workdays/createData', [WorkdayController::class, 'createData'])->name('workday.createData');

        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/createData', [UserController::class, 'createData'])->name('users.createData');

        Route::post('passengers', [PassengerController::class, 'store'])->name('passenger.store');
        Route::get('passengers/createData', [PassengerController::class, 'createData'])->name('passenger.createData');

        Route::get('requests', [RequestController::class, 'index']);
        Route::post('requests', [RequestController::class, 'store'])->name('request.store');
        Route::post('requests/{request}/updateStatus', [RequestController::class, 'updateStatus'])->name('request.updateStatus');
        Route::get('requests/createData', [RequestController::class, 'createData'])->name('request.createData');
        
        Route::get('phones/{phone}', [PhoneController::class, 'show'])->name('phone.show');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->name('category.show');
        
        Route::post('metro/stations/findBestRoutes', [MetroStationController::class, 'findBestRoutes'])->name('metro.stations.findBestRoutes');
        
        Route::get('statuses', [StatusController::class, 'index']);
        // Route::get('requests/distribution', [RequestController::class, 'distribution']);
        
    });
    Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
});

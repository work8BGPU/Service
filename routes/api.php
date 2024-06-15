<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api')->group(function() {
    Route::middleware('guest')->group(function() {
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('register', [AuthController::class, 'register'])->name('register');
    });

    Route::middleware('auth:api')->group(function() {
        Route::post('me', [AuthController::class, 'me'])->name('me');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::post('employees', [EmployeeController::class, 'store'])->name('employee.store');
        Route::get('employees/createData', [EmployeeController::class, 'createData'])->name('employee.createData');
    });
    Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
});

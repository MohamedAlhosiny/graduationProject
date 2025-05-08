<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VideoController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/echo')->group(function () {
        Route::post('', [VideoController::class, 'store']);
        Route::get('', [VideoController::class, 'index']);
        Route::get('/{id}', [VideoController::class, 'show']);
        Route::delete('/{id}', [VideoController::class, 'destroy']);
        Route::post('/{id}', [VideoController::class, 'update']);
    });
});

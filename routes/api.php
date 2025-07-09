<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WordController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\Model3DController;
use App\Http\Controllers\PasswordResetController;
// for User
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Password-reset
Route::post('password/forget', [PasswordResetController::class, 'requestReset']);
Route::post('password/verify', [PasswordResetController::class, 'verifyCode']);
Route::post('password/reset',  [PasswordResetController::class, 'resetPassword']);



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // for Charcters
    Route::prefix('/model')->group(function () {
        Route::post('', [Model3DController::class, 'store']);
        Route::get('', [Model3DController::class, 'index']);
        Route::get('/{id}', [Model3DController::class, 'show']);
        Route::post('/{id}', [Model3DController::class, 'update']);
        Route::delete('/{id}', [Model3DController::class, 'destroy']);
    });

    //for Vidoes
    Route::prefix('/echo')->group(function () {

        Route::post('', [VideoController::class, 'store']);

        Route::get('', [VideoController::class, 'index']);
        Route::get('/{id}', [VideoController::class, 'show']);
        Route::delete('/{id}', [VideoController::class, 'destroy']);
        Route::post('/{id}', [VideoController::class, 'update']);
        Route::post('/process-video', [VideoController::class, 'sendToFastAPI']);
    });

    //for Words
    Route::prefix('/word')->group(function () {
        Route::get('', [WordController::class, 'index']);
    });
});

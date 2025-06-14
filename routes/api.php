<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Model3DController;
use App\Http\Controllers\VideoController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout' , [AuthController::class , 'logout']);

    Route::prefix('/model')->group(function () {
        Route::post('' , [Model3DController::class , 'store']);
        Route::get('' , [Model3DController::class , 'index']);
        Route::get('/{id}' , [Model3DController::class , 'show']);


        });

    Route::prefix('/echo')->group(function () {

        Route::post('', [VideoController::class, 'store']);
        Route::get('', [VideoController::class, 'index']);
        Route::get('/{id}', [VideoController::class, 'show']);
        Route::delete('/{id}', [VideoController::class, 'destroy']);
        Route::post('/{id}', [VideoController::class, 'update']);


    });


});

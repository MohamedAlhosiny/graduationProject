<?php

use App\Http\Controllers\AuthContoller;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register' , [AuthContoller::class , 'register']);
Route::post('/login' , [AuthContoller::class , 'login']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/echo')->group(function() {
        Route::post('', [VideoController::class , 'store']);
        Route::get('', [VideoController::class , 'index']);
        Route::get('/{id}', [VideoController::class , 'show']);
        Route::delete('/{id}', [VideoController::class , 'destroy']);
        Route::post('/{id}' , [VideoController::class , 'update']);

});



});

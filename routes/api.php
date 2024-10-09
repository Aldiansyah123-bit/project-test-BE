<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PosterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('poster',[PosterController::class, 'index']);
    Route::post('poster',[PosterController::class, 'store']);
    Route::put('poster',[PosterController::class, 'update']);
    Route::delete('poster',[PosterController::class, 'destroy']);
});

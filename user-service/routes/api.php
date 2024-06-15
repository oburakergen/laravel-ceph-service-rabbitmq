<?php

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

Route::prefix('/security/v1')->group(function () {
    Route::apiResource('users', \App\Http\Controllers\UserApi::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
});
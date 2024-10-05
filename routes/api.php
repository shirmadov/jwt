<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\JWTAuthMiddleware;


Route::prefix('auth')->middleware(JWTAuthMiddleware::class)->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('login', [AuthController::class,'login'])->withoutMiddleware(JWTAuthMiddleware::class);
});

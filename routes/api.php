<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\GuestOnly;
use Illuminate\Support\Facades\Route;

Route::name('auth.')->prefix('auth')->group(function () {
    // login
    Route::post('login', [AuthController::class, 'login'])
        ->name('login')
        ->middleware(GuestOnly::class);

    // logout
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout')
        ->middleware('auth:sanctum');
});

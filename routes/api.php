<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware('throttle:60,1')->group(function () {

    Route::post('/users', [UserController::class, 'create']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/user', [UserController::class, 'getAuthenticatedUser']);
        Route::post('/logout', [UserController::class, 'logout']);

        // Add other protected routes here
        // Example:
        // Route::post('/profile', [UserController::class, 'updateProfile']);
        // Route::post('/settings', [UserController::class, 'updateSettings']);
    });
});

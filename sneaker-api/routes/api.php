<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProfileController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Group all routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    // User profile route (no need to add 'auth:sanctum' again)
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    
    // Orders routes
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders', [OrderController::class, 'index']);
    
    // Profile route (access to logged-in user's profile)
    Route::get('user/profile', [ProfileController::class, 'show']);
});

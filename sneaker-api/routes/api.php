<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\FavouriteController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Group all routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    // User profile route (no need to add 'auth:sanctum' again)
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::middleware('auth:sanctum')->put('/user/update', [ProfileController::class, 'update']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    
    // Profile route (access to logged-in user's profile)
    Route::get('user/profile', [ProfileController::class, 'show']);
    Route::get('/favourites', [FavouriteController::class, 'index']);
    Route::post('/favourites', [FavouriteController::class, 'store']);
    Route::delete('/favourites/{id}', [FavouriteController::class, 'destroy']);
});

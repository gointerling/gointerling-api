<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserMerchantController;

// testing route
Route::get('test', function () {
    // dummy user data response
    return response()->json([
        'message' => 'Hello World!',
        'data' => [
            'name' => 'John Doe',
            'email' => 'johndoe@mail.com',
            'photo' => 'https://fastly.picsum.photos/id/579/200/300.jpg?hmac=9MD8EV4Jl9EqKLkTj5kyNdBUKQWyHk2m4pE4UCBGc8Q',
        ],
    ]);
});

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::get('auth/google/redirect', [AuthController::class, 'googleRedirect']);
Route::get('auth/google/callback', [AuthController::class, 'googleCallback']);

// Protected routes (require authentication)
Route::middleware('auth:api')->group(function () {
    // users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    
    // merchants
    Route::get('/users-merchants', [UserMerchantController::class, 'index']);
    Route::get('/users-merchants/{merchant}', [UserMerchantController::class, 'show']);
    Route::post('/users-merchants', [UserMerchantController::class, 'store']);
    Route::put('/users-merchants/{merchant}', [UserMerchantController::class, 'update']);
    Route::delete('/users-merchants/{merchant}', [UserMerchantController::class, 'destroy']);
});
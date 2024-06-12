<?php

use App\Http\Controllers\AuthController;

// testing route
Route::get('test', function () {
    return response()->json(['message' => 'Hello World!']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('auth/google/redirect', [AuthController::class, 'googleRedirect']);
Route::get('auth/google/callback', [AuthController::class, 'googleCallback']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('user', function () {
        return auth()->user();
    });
    
    Route::middleware('role:admin')->group(function () {
        // Routes for admin
    });
    
    Route::middleware('role:user')->group(function () {
        // Routes for user
    });
});

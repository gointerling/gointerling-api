<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserMerchantController;
use App\Http\Controllers\FileUploadController;


Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::get('auth/google/redirect', [AuthController::class, 'googleRedirect']);
Route::get('auth/google/callback', [AuthController::class, 'googleCallback']);

// Protected routes (require authentication)
Route::middleware('auth:api')->group(function () {
    // my profile
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/my/merchant', [UserController::class, 'showMyUserMerchantDetail']);
    Route::put('/my/merchant', [UserMerchantController::class, 'updateMyMerchant']);
    
    // users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    
    // users update with id
    Route::put('/users/{user}/update-password', [UserController::class, 'updatePassword']);
    Route::put('/users/{user}/update-role', [UserController::class, 'updateRole']);
    
    // merchants
    Route::get('/users-merchants', [UserMerchantController::class, 'index']);
    Route::get('/users-merchants/{merchant}', [UserMerchantController::class, 'showMerchantDetail']);
    Route::post('/users-merchants', [UserMerchantController::class, 'store']);
    Route::put('/users-merchants/{merchant}', [UserMerchantController::class, 'update']);
    Route::delete('/users-merchants/{merchant}', [UserMerchantController::class, 'destroy']);
    

    // files
    Route::get('files', [FileUploadController::class, 'index']);
    Route::post('files', [FileUploadController::class, 'store']);
    Route::get('files/{id}', [FileUploadController::class, 'show']);
    Route::put('files/{id}', [FileUploadController::class, 'update']);
    Route::delete('files/{id}', [FileUploadController::class, 'destroy']);
});
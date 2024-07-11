<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserMerchantController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AdvertisementPackageController;
use App\Http\Controllers\SubscriptionPackageController;
use App\Http\Controllers\SettingController;


Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::get('auth/google/redirect', [AuthController::class, 'googleRedirect']);
Route::get('auth/google/callback', [AuthController::class, 'googleCallback']);
Route::get('advertisements/display', [AdvertisementController::class, 'display']);
Route::get('advertisement-packages', [AdvertisementPackageController::class, 'index']);
Route::post('files/ads', [FileUploadController::class, 'store']);

// Protected routes (require authentication)
Route::middleware('auth:api')->group(function () {

    // Personalize user
        // profile
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateMyProfile']);
        Route::put('/profile/password', [AuthController::class, 'updateMyPassword']);
        
        // merchants
        Route::get('/my/merchant', [UserController::class, 'showMyUserMerchantDetail']);
        Route::put('/my/merchant', [UserMerchantController::class, 'updateMyMerchant']);
        Route::put('/my/merchant/file', [UserMerchantController::class, 'updateMyMerchantFile']);
        Route::put('/my/merchant/status', [UserController::class, 'updateMyMerchantStatus']);
        
        // services
        Route::get('/my/service', [UserController::class, 'showMyUserMerchantServiceDetail']);
        Route::put('/my/service/{service}', [ServiceController::class, 'updateMyService']);

        // orders
        Route::get('/my/orders', [OrderController::class, 'getMyOrder']);
        Route::get('/my/merchant/orders', [OrderController::class, 'getMyMerchantOrder']);
        Route::post('/my/orders', [OrderController::class, 'setMyOrder']);
        Route::put('/my/orders/{order}', [OrderController::class, 'updateMyOrder']);

        // ads
        Route::get('/my/advertisements', [AdvertisementController::class, 'getMyAds']);
        Route::post('/my/advertisements', [AdvertisementController::class, 'storeMyAds']);
        Route::put('/my/advertisements/{advertisement}/proof-of-payment', [AdvertisementController::class, 'updateMyAdsPayment']);

        // subscriptions
        Route::get('/my/subscriptions', [SubscriptionPackageController::class, 'getMySubscription']);
        Route::post('/my/subscriptions', [SubscriptionPackageController::class, 'storeMySubscription']);
        Route::put('/my/subscriptions/{subscription}/proof-of-payment', [SubscriptionPackageController::class, 'updateMySubscriptionPayment']);
    
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
    Route::put('/users-merchants/{user}', [UserMerchantController::class, 'update']);
    Route::delete('/users-merchants/{user}', [UserMerchantController::class, 'destroy']);
    Route::put('/users-merchants/{user}/status', [UserMerchantController::class, 'updateMerchantStatus']);

    // orders
    Route::get('/orders', [OrderController::class, 'index']);
    // Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::put('/orders/{order}', [OrderController::class, 'update']);
    Route::delete('/orders/{order}', [OrderController::class, 'destroy']);
    Route::put('/orders/{order}/client-status', [OrderController::class, 'updateClientStatus']);
    
    // files
    Route::get('files', [FileUploadController::class, 'index']);
    Route::post('files', [FileUploadController::class, 'store']);
    Route::get('files/{id}', [FileUploadController::class, 'show']);
    Route::put('files/{id}', [FileUploadController::class, 'update']);
    Route::delete('files/{id}', [FileUploadController::class, 'destroy']);

    // skills
    Route::get('skills', [SkillController::class, 'index']);
    Route::post('skills', [SkillController::class, 'store']);
    Route::get('skills/{skill}', [SkillController::class, 'show']);
    Route::put('skills/{skill}', [SkillController::class, 'update']);
    Route::delete('skills/{skill}', [SkillController::class, 'destroy']);

    // languages
    Route::get('/languages', [LanguageController::class, 'index']);
    Route::get('/languages/{id}', [LanguageController::class, 'show']);
    Route::post('/languages', [LanguageController::class, 'store']);
    Route::put('/languages/{id}', [LanguageController::class, 'update']);
    Route::delete('/languages/{id}', [LanguageController::class, 'destroy']);

    // services
    Route::get('/services', [ServiceController::class, 'index']);
    Route::post('/services', [ServiceController::class, 'store']);
    Route::get('/services/{service}', [ServiceController::class, 'show']);
    Route::put('/services/{service}', [ServiceController::class, 'update']);
    Route::delete('/services/{service}', [ServiceController::class, 'destroy']);
    Route::get('/user/merchant-services', [ServiceController::class, 'getUserMerchantServices']);

    // ads
    Route::get('/advertisements', [AdvertisementController::class, 'index']);
    Route::post('/advertisements', [AdvertisementController::class, 'store']);
    Route::get('/advertisements/{advertisement}', [AdvertisementController::class, 'show']);
    Route::put('/advertisements/{advertisement}', [AdvertisementController::class, 'update']);
    Route::delete('/advertisements/{advertisement}', [AdvertisementController::class, 'destroy']);
    Route::put('/advertisements/{advertisement}/status', [AdvertisementController::class, 'setAdsStatus']);

    // ads packages
    Route::post('/advertisement-packages', [AdvertisementPackageController::class, 'store']);
    Route::get('/advertisement-packages/{advertisementPackage}', [AdvertisementPackageController::class, 'show']);
    Route::put('/advertisement-packages/{advertisementPackage}', [AdvertisementPackageController::class, 'update']);
    Route::delete('/advertisement-packages/{advertisementPackage}', [AdvertisementPackageController::class, 'destroy']);

 
    // settings
    Route::get('/settings', [SettingController::class, 'getSetting']);
    Route::get('/settings/bank', [SettingController::class, 'getSettingBank']);
    Route::put('/settings', [SettingController::class, 'updateSetting']);

    // subscriptions package
    Route::get('/subscriptions-packages', [SubscriptionPackageController::class, 'index']);
    Route::post('/subscriptions', [SubscriptionPackageController::class, 'store']);
    Route::get('/subscriptions/{subscription}', [SubscriptionPackageController::class, 'show']);
    Route::put('/subscriptions/{subscription}', [SubscriptionPackageController::class, 'update']);
    Route::delete('/subscriptions/{subscription}', [SubscriptionPackageController::class, 'destroy']);
    Route::put('/subscriptions/{subscription}/status', [SubscriptionPackageController::class, 'updateStatus']);
    Route::put('/subscriptions/{subscription}/proof-of-payment', [SubscriptionPackageController::class, 'updatePayment']);

    
});

   
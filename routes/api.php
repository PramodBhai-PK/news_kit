<?php

use App\Http\Controllers\Api\V1\AdvertisementController;
use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CitizenTipController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\ConfigController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\NewsletterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| NewsKit REST API V1 Routes
|--------------------------------------------------------------------------
| Production-grade API endpoints for Mobile App (Flutter / React Native)
| and third-party integrations.
*/

Route::prefix('v1')->group(function () {

    // App Configuration, Branding & Localization
    Route::get('/config', [ConfigController::class, 'index']);

    // Articles & News
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/breaking', [ArticleController::class, 'breaking']);
    Route::get('/articles/trending', [ArticleController::class, 'trending']);
    Route::get('/articles/featured', [ArticleController::class, 'featured']);
    Route::get('/articles/videos', [ArticleController::class, 'videos']);
    Route::get('/articles/{slug}', [ArticleController::class, 'show']);
    Route::get('/articles/{slug}/comments', [CommentController::class, 'index']);
    Route::post('/articles/{slug}/comments', [CommentController::class, 'store']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);

    // Hyperlocal Location (States & Cities)
    Route::get('/locations/states', [LocationController::class, 'states']);
    Route::get('/locations/states/{slug}', [LocationController::class, 'state']);
    Route::get('/locations/cities', [LocationController::class, 'cities']);
    Route::get('/locations/cities/{slug}', [LocationController::class, 'city']);

    // Citizen Journalism / Breaking News Tips
    Route::post('/citizen-tips', [CitizenTipController::class, 'store']);

    // Newsletter Subscription
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);

    // Advertisements & Sponsored Content
    Route::get('/ads', [AdvertisementController::class, 'index']);
    Route::post('/ads/{id}/click', [AdvertisementController::class, 'click']);

    // Authentication (Sanctum)
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);

    // Authenticated User Endpoints
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
    });
});

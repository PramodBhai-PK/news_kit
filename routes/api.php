<?php

use App\Http\Controllers\Api\V1\AdvertisementController;
use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CitizenTipController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\ConfigController;
use App\Http\Controllers\Api\V1\EpaperController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\NewsletterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| NewsKit REST API V1 Routes
|--------------------------------------------------------------------------
| Production-grade API endpoints for Mobile App (Flutter / React Native)
| Protected with rate-limiting (throttle) and Sanctum authentication.
*/

Route::prefix('v1')->middleware(['throttle:60,1'])->group(function () {

    // App Configuration, Branding & Localization
    Route::get('/config', [ConfigController::class, 'index']);

    // Articles & News
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/breaking', [ArticleController::class, 'breaking']);
    Route::get('/articles/trending', [ArticleController::class, 'trending']);
    Route::get('/articles/featured', [ArticleController::class, 'featured']);
    Route::get('/articles/videos', [ArticleController::class, 'videos']);
    Route::get('/articles/{slug}', [ArticleController::class, 'show']);
    Route::get('/articles/{slug}/ai-summary', [ArticleController::class, 'aiSummary']);
    Route::get('/articles/{slug}/comments', [CommentController::class, 'index']);
    Route::post('/articles/{slug}/comments', [CommentController::class, 'store'])->middleware('throttle:10,1');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);

    // Hyperlocal Location (States & Cities)
    Route::get('/locations/states', [LocationController::class, 'states']);
    Route::get('/locations/states/{slug}', [LocationController::class, 'state']);
    Route::get('/locations/cities', [LocationController::class, 'cities']);
    Route::get('/locations/cities/{slug}', [LocationController::class, 'city']);

    // Citizen Journalism / Breaking News Tips (Strict rate-limit to prevent file spam)
    Route::post('/citizen-tips', [CitizenTipController::class, 'store'])->middleware('throttle:5,1');

    // Newsletter Subscription
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->middleware('throttle:10,1');

    // Advertisements & Sponsored Content
    Route::get('/ads', [AdvertisementController::class, 'index']);
    Route::post('/ads/impressions', [AdvertisementController::class, 'batchImpressions']);
    Route::post('/ads/{id}/impression', [AdvertisementController::class, 'impression']);
    Route::post('/ads/{id}/view', [AdvertisementController::class, 'impression']);
    Route::post('/ads/{id}/click', [AdvertisementController::class, 'click']);

    // Digital E-Paper & Daily Newspaper Editions
    Route::get('/epaper/today', [EpaperController::class, 'today']);
    Route::get('/epaper/editions', [EpaperController::class, 'index']);
    Route::get('/epaper/{idOrSlug}', [EpaperController::class, 'show']);
    Route::post('/epaper/{id}/view', [EpaperController::class, 'view']);
    Route::post('/epaper/{id}/download', [EpaperController::class, 'download']);

    // Authentication (Sanctum) - Strict rate-limit to prevent brute-force attacks
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::post('/auth/register', [AuthController::class, 'register'])->middleware('throttle:5,1');

    // Authenticated User Endpoints
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
    });
});

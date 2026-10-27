<?php

use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CitizenTipController as AdminCitizenTipController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitizenTipController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\PressCardVerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public News Portal Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PortalController::class, 'index'])->name('portal.index');
Route::get('/category/{slug}', [PortalController::class, 'category'])->name('portal.category');
Route::get('/tag/{slug}', [PortalController::class, 'tag'])->name('portal.tag');
Route::get('/article/{slug}', [PortalController::class, 'article'])->name('portal.article');
Route::post('/article/{slug}/comments', [PortalController::class, 'commentStore'])->name('portal.comment.store');
Route::get('/search', [PortalController::class, 'search'])->name('portal.search');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('portal.newsletter.subscribe');
Route::get('/ad/{advertisement}/click', [PortalController::class, 'adClick'])->name('portal.ad.click');
Route::get('/article/{article}/ad-click', [PortalController::class, 'articleAdClick'])->name('portal.article.ad.click');
Route::get('/locale/{locale}', [PortalController::class, 'setLocale'])->name('portal.locale');

// Official Digital Press ID Card Live Public QR Verification Route
Route::get('/verify-press/{code}', [PressCardVerificationController::class, 'verify'])->name('press.verify');

// Citizen Journalism & News Tip Whistleblower Routes
Route::get('/citizen-tip', [CitizenTipController::class, 'create'])->name('portal.citizen-tip');
Route::post('/citizen-tip', [CitizenTipController::class, 'store'])->name('portal.citizen-tip.store');

// Hyperlocal State & City News Routes
Route::get('/state/{slug}', [PortalController::class, 'state'])->name('portal.state');
Route::get('/city/{slug}', [PortalController::class, 'city'])->name('portal.city');
Route::get('/set-city/{slug}', [PortalController::class, 'setCity'])->name('portal.set-city');

// SEO Architecture: XML Sitemaps, Google News Sitemap & RSS 2.0 Feeds
Route::get('/sitemap.xml', [PortalController::class, 'sitemap'])->name('portal.sitemap');
Route::get('/news-sitemap.xml', [PortalController::class, 'newsSitemap'])->name('portal.news-sitemap');
Route::get('/feed', [PortalController::class, 'feed'])->name('portal.feed');
Route::get('/rss.xml', fn () => redirect()->route('portal.feed', [], 301));

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Admin & CMS Routes (Auth Middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // News Articles Management
    Route::resource('articles', ArticleController::class);

    // Categories Management
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);

    // Comments Moderation
    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/comments/{comment}/toggle', [CommentController::class, 'toggleApprove'])->name('comments.toggle');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Newsletter Subscribers Management
    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
    Route::post('/subscribers/{subscriber}/toggle', [SubscriberController::class, 'toggle'])->name('subscribers.toggle');
    Route::delete('/subscribers/{subscriber}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');

    // Advertisements Management (Custom Image & Google AdSense HTML)
    Route::resource('advertisements', AdvertisementController::class)->except(['create', 'show', 'edit']);
    Route::post('/advertisements/{advertisement}/toggle', [AdvertisementController::class, 'toggle'])->name('advertisements.toggle');

    // Single-File & Single-Page Branding Hub
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Live Theme Customizer & Real-Time Portal Studio
    Route::get('/theme', [ThemeController::class, 'index'])->name('theme.index');
    Route::post('/theme/publish', [ThemeController::class, 'publish'])->name('theme.publish');
    Route::post('/theme/reset', [ThemeController::class, 'reset'])->name('theme.reset');

    // Team & User Roles Management (Admins, Editors, Publishers, Reporters)
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
    Route::post('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
    Route::get('/users/{user}/press-card', [UserController::class, 'pressCard'])->name('users.press-card');
    Route::post('/users/{user}/press-card', [UserController::class, 'updatePressCard'])->name('users.press-card.update');

    // Citizen News Tips & Whistleblower Management
    Route::get('/citizen-tips', [AdminCitizenTipController::class, 'index'])->name('citizen-tips.index');
    Route::get('/citizen-tips/{citizenTip}', [AdminCitizenTipController::class, 'show'])->name('citizen-tips.show');
    Route::post('/citizen-tips/{citizenTip}/status', [AdminCitizenTipController::class, 'updateStatus'])->name('citizen-tips.status');
    Route::post('/citizen-tips/{citizenTip}/convert', [AdminCitizenTipController::class, 'convertToArticle'])->name('citizen-tips.convert');
    Route::delete('/citizen-tips/{citizenTip}', [AdminCitizenTipController::class, 'destroy'])->name('citizen-tips.destroy');

    // Self-Service Profile & Digital Press ID Card (For All Logged-in Staff)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/profile/press-card', [ProfileController::class, 'pressCard'])->name('profile.press-card');
});

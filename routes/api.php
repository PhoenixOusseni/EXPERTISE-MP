<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\VideoStreamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->middleware('throttle:6,1');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:6,1');

    Route::middleware(['auth:sanctum', 'active'])->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// Formules d'abonnement : consultables sans être connecté.
Route::get('plans', [SubscriptionController::class, 'plans']);

// Flux vidéo protégé par URL signée (le lecteur <video> ne peut pas envoyer de jeton Bearer).
Route::get('videos/{video}/stream/{user}', VideoStreamController::class)
    ->middleware('signed')
    ->name('videos.stream');

/*
|--------------------------------------------------------------------------
| Espace apprenant
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::get('categories', [CatalogController::class, 'categories']);
    Route::get('videos', [CatalogController::class, 'index']);
    Route::get('videos/{video:slug}', [CatalogController::class, 'show']);

    Route::get('subscriptions', [SubscriptionController::class, 'index']);
    Route::get('subscriptions/current', [SubscriptionController::class, 'current']);
    Route::post('subscriptions', [SubscriptionController::class, 'store']);
    Route::post('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel']);
});

/*
|--------------------------------------------------------------------------
| Espace administrateur
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth:sanctum', 'active', 'admin'])
    ->name('api.admin.')
    ->group(function () {
        Route::get('dashboard', Admin\DashboardController::class)->name('dashboard');

        Route::apiResource('categories', Admin\CategoryController::class);

        Route::apiResource('videos', Admin\VideoController::class);
        Route::post('videos/{video}/publish', [Admin\VideoController::class, 'publish'])->name('videos.publish');
        Route::post('videos/{video}/unpublish', [Admin\VideoController::class, 'unpublish'])->name('videos.unpublish');

        Route::apiResource('plans', Admin\PlanController::class);

        Route::apiResource('subscriptions', Admin\SubscriptionController::class)->except('update');
        Route::post('subscriptions/{subscription}/activate', [Admin\SubscriptionController::class, 'activate'])->name('subscriptions.activate');
        Route::post('subscriptions/{subscription}/cancel', [Admin\SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
        Route::post('subscriptions/{subscription}/extend', [Admin\SubscriptionController::class, 'extend'])->name('subscriptions.extend');

        Route::apiResource('users', Admin\UserController::class)->only(['index', 'show', 'update']);
    });

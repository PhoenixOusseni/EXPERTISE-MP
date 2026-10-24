<?php

use App\Http\Controllers\Web\Admin;
use App\Http\Controllers\Web\Apprenant;
use App\Http\Controllers\Web\Auth\AuthController;
use App\Http\Controllers\Web\Apprenant\ContactController;
use App\Http\Controllers\Web\Apprenant\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Page d'accueil publique
|--------------------------------------------------------------------------
*/

Route::get('/', [Apprenant\CatalogueController::class, 'welcome'])->name('welcome');
Route::get('/publicites/{publicite}', [Apprenant\PubliciteController::class, 'show'])->name('publicites.show');

/*
|--------------------------------------------------------------------------
| Authentification (invités seulement)
|--------------------------------------------------------------------------
*/

// For storage link
Route::get('/link', function () {
    $targetFolder = storage_path('app/public');
    $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/storage';
    symlink($targetFolder, $linkFolder);
});

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login']);
    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register']);
});

Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Espace apprenant
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/catalogue', [Apprenant\CatalogueController::class, 'index'])->name('catalogue');
    Route::get('/videos/{slug}', [Apprenant\CatalogueController::class, 'show'])->name('videos.show');

    // Publications (abonnés)
    Route::get('/publications', [Apprenant\PublicationController::class, 'index'])->name('publications.index');
    Route::get('/publications/{publication}', [Apprenant\PublicationController::class, 'show'])->name('publications.show');

    Route::get('/formules', [Apprenant\AbonnementController::class, 'plans'])->name('formules');
    Route::get('/souscrire/{plan}', [Apprenant\AbonnementController::class, 'checkout'])->name('subscriptions.checkout');
    Route::get('/mon-abonnement', [Apprenant\AbonnementController::class, 'index'])->name('mon-abonnement');
    Route::post('/abonnements', [Apprenant\AbonnementController::class, 'store'])->name('subscriptions.store');
    Route::post('/abonnements/{subscription}/annuler', [Apprenant\AbonnementController::class, 'cancel'])->name('subscriptions.cancel');

    // Profil
    Route::get('/mon-profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/mon-profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/mon-profil/mot-de-passe', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/mon-profil/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/mon-profil/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');

    // Contact
    Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    Route::get('/mes-demandes', [ContactController::class, 'index'])->name('contact.index');
    Route::get('/mes-demandes/{contactRequest}', [ContactController::class, 'show'])->name('contact.show');
});

/*
|--------------------------------------------------------------------------
| Espace administrateur
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth', 'active', 'admin'])
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        // Vidéos
        Route::get('videos', [Admin\VideoController::class, 'index'])->name('videos.index');
        Route::get('videos/creer', [Admin\VideoController::class, 'create'])->name('videos.create');
        Route::post('videos', [Admin\VideoController::class, 'store'])->name('videos.store');
        Route::get('videos/{video}/modifier', [Admin\VideoController::class, 'edit'])->name('videos.edit');
        Route::put('videos/{video}', [Admin\VideoController::class, 'update'])->name('videos.update');
        Route::delete('videos/{video}', [Admin\VideoController::class, 'destroy'])->name('videos.destroy');
        Route::post('videos/{video}/publier', [Admin\VideoController::class, 'publish'])->name('videos.publish');
        Route::post('videos/{video}/depublier', [Admin\VideoController::class, 'unpublish'])->name('videos.unpublish');
        Route::get('videos/{video}/preview-stream', [Admin\VideoController::class, 'previewStream'])->name('videos.preview-stream');

        // Catégories
        Route::get('categories', [Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{category}/modifier', [Admin\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{category}', [Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

        // Formules d'abonnement
        Route::get('formules', [Admin\PlanController::class, 'index'])->name('plans.index');
        Route::post('formules', [Admin\PlanController::class, 'store'])->name('plans.store');
        Route::get('formules/{plan}/modifier', [Admin\PlanController::class, 'edit'])->name('plans.edit');
        Route::put('formules/{plan}', [Admin\PlanController::class, 'update'])->name('plans.update');
        Route::delete('formules/{plan}', [Admin\PlanController::class, 'destroy'])->name('plans.destroy');

        // Abonnements
        Route::get('abonnements', [Admin\SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::get('abonnements/creer', [Admin\SubscriptionController::class, 'create'])->name('subscriptions.create');
        Route::post('abonnements', [Admin\SubscriptionController::class, 'store'])->name('subscriptions.store');
        Route::get('abonnements/{subscription}', [Admin\SubscriptionController::class, 'show'])->name('subscriptions.show');
        Route::post('abonnements/{subscription}/activer', [Admin\SubscriptionController::class, 'activate'])->name('subscriptions.activate');
        Route::post('abonnements/{subscription}/annuler', [Admin\SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
        Route::post('abonnements/{subscription}/prolonger', [Admin\SubscriptionController::class, 'extend'])->name('subscriptions.extend');
        Route::delete('abonnements/{subscription}', [Admin\SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

        // Apprenants
        Route::get('apprenants', [Admin\UserController::class, 'index'])->name('users.index');
        Route::get('apprenants/{user}', [Admin\UserController::class, 'show'])->name('users.show');
        Route::post('apprenants/{user}/toggle', [Admin\UserController::class, 'toggle'])->name('users.toggle');

        // Contact / Messages
        Route::get('messages', [Admin\ContactController::class, 'index'])->name('contact.index');
        Route::get('messages/{contactRequest}', [Admin\ContactController::class, 'show'])->name('contact.show');
        Route::post('messages/{contactRequest}/repondre', [Admin\ContactController::class, 'reply'])->name('contact.reply');
        Route::post('messages/{contactRequest}/statut', [Admin\ContactController::class, 'updateStatus'])->name('contact.status');

        // Profil admin
        Route::get('profil/mot-de-passe', [Admin\ProfileController::class, 'showPasswordForm'])->name('profile.password');
        Route::put('profil/mot-de-passe', [Admin\ProfileController::class, 'updatePassword'])->name('profile.password.update');

        // Publications
        Route::get('publications', [Admin\PublicationController::class, 'index'])->name('publications.index');
        Route::get('publications/creer', [Admin\PublicationController::class, 'create'])->name('publications.create');
        Route::post('publications', [Admin\PublicationController::class, 'store'])->name('publications.store');
        Route::get('publications/{publication}/modifier', [Admin\PublicationController::class, 'edit'])->name('publications.edit');
        Route::put('publications/{publication}', [Admin\PublicationController::class, 'update'])->name('publications.update');
        Route::delete('publications/{publication}', [Admin\PublicationController::class, 'destroy'])->name('publications.destroy');
        Route::post('publications/{publication}/publier', [Admin\PublicationController::class, 'publish'])->name('publications.publish');
        Route::post('publications/{publication}/depublier', [Admin\PublicationController::class, 'unpublish'])->name('publications.unpublish');

        // Publicités
        Route::get('publicites', [Admin\PubliciteController::class, 'index'])->name('publicites.index');
        Route::get('publicites/creer', [Admin\PubliciteController::class, 'create'])->name('publicites.create');
        Route::post('publicites', [Admin\PubliciteController::class, 'store'])->name('publicites.store');
        Route::get('publicites/{publicite}/modifier', [Admin\PubliciteController::class, 'edit'])->name('publicites.edit');
        Route::put('publicites/{publicite}', [Admin\PubliciteController::class, 'update'])->name('publicites.update');
        Route::delete('publicites/{publicite}', [Admin\PubliciteController::class, 'destroy'])->name('publicites.destroy');
    });

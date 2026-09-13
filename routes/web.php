<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Distributor\DashboardController as DistributorDashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Producer\DashboardController as ProducerDashboardController;
use App\Http\Controllers\WithdrawalRequestController;
use App\Http\Controllers\SuperAdmin\AdminController;
use App\Http\Controllers\SuperAdmin\AuditLogController;
use App\Http\Controllers\SuperAdmin\ComplaintController as SuperAdminComplaintController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\NotificationController as SuperAdminNotificationController;
use App\Http\Controllers\SuperAdmin\OrderController as SuperAdminOrderController;
use App\Http\Controllers\SuperAdmin\WithdrawalController as SuperAdminWithdrawalController;
use App\Http\Controllers\SuperAdmin\PlaceholderController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/home', fn () => redirect('/', 301));

Route::get('/login', fn () => redirect('/connexion', 301));
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', fn () => redirect('/inscription', 301));
Route::get('/register/{type}', [RegisterController::class, 'showRegistrationForm']);
Route::post('/register/{type}', [RegisterController::class, 'register']);

Route::get('/forgot-password', fn () => redirect('/mot-de-passe-oublie', 301));
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);

Route::get('/two-factor-challenge', fn () => redirect('/double-authentification', 301));
Route::post('/two-factor-challenge', [LoginController::class, 'verifyTwoFactor']);

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/map', fn () => redirect('/carte-agricole', 301));
Route::get('/locations', fn () => redirect('/mes-localisations', 301));
Route::get('/products', fn () => redirect('/mes-produits', 301));
Route::get('/catalogue-produits', [ProductCatalogController::class, 'index'])->name('catalog.products');
Route::get('/catalogue-produits/{product}', [ProductCatalogController::class, 'show'])->name('catalog.products.show');
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/panier/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/panier/commander', [CartController::class, 'checkout'])->name('cart.checkout');

Route::get('/password/reset/{token}', function (string $token) {
    return redirect()->route('password.reset', ['token' => $token]);
});

Route::post('/reset-password', [ForgotPasswordController::class, 'reset']);

Route::middleware('auth')->get('/dashboard', function () {
    return redirect(auth()->user()->homeUrl());
})->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/connexion', [LoginController::class, 'login']);

    Route::get('/inscription', [RegisterController::class, 'showRegistrationHub'])->name('register');
    Route::get('/inscription/{type}', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
    Route::post('/inscription/{type}', [RegisterController::class, 'register'])->name('register.submit');

    Route::get('/mot-de-passe-oublie', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reinitialiser-mot-de-passe/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reinitialiser-mot-de-passe', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

Route::get('/double-authentification', [LoginController::class, 'showTwoFactorChallenge'])->name('two-factor.challenge');
Route::post('/double-authentification', [LoginController::class, 'verifyTwoFactor'])->name('two-factor.verify');

Route::post('/deconnexion', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->get('/carte-agricole', [MapController::class, 'index'])->name('agri.map');
Route::middleware('auth')->prefix('mes-localisations')->name('locations.')->group(function () {
    Route::get('/', [LocationController::class, 'index'])->name('index');
    Route::get('/creer', [LocationController::class, 'create'])->name('create');
    Route::post('/', [LocationController::class, 'store'])->name('store');
    Route::get('/{location}/modifier', [LocationController::class, 'edit'])->name('edit');
    Route::put('/{location}', [LocationController::class, 'update'])->name('update');
    Route::delete('/{location}', [LocationController::class, 'destroy'])->name('destroy');
    Route::post('/{location}/principale', [LocationController::class, 'primary'])->name('primary');
});

Route::middleware('auth')->group(function () {
    Route::get('/parametres', [\App\Http\Controllers\SettingsController::class, 'edit'])->name('settings.edit');
    Route::get('/parametres/profil', [\App\Http\Controllers\SettingsController::class, 'profile'])->name('settings.profile');
    Route::put('/parametres/profil', [\App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::get('/parametres/mot-de-passe', [\App\Http\Controllers\SettingsController::class, 'password'])->name('settings.password');
    Route::put('/parametres/mot-de-passe', [\App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::get('/parametres/supprimer', [\App\Http\Controllers\SettingsController::class, 'delete'])->name('settings.delete');
    Route::delete('/parametres/supprimer', [\App\Http\Controllers\SettingsController::class, 'destroy'])->name('settings.destroy');
    Route::middleware(['role:admin,super_admin'])->group(function () {
        Route::get('/parametres/2fa', [TwoFactorController::class, 'setup'])->name('settings.two-factor');
        Route::post('/parametres/2fa', [TwoFactorController::class, 'confirm'])->name('settings.two-factor.confirm');
        Route::post('/parametres/2fa/desactiver', [TwoFactorController::class, 'disable'])->name('settings.two-factor.disable');
    });
});

Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:producer'])->prefix('producteur')->name('producer.')->group(function () {
    Route::get('/dashboard', [ProducerDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:producer,distributor'])->prefix('mes-produits')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/creer', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{product}/modifier', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', 'role:client'])->prefix('mes-reclamations')->name('claims.')->group(function () {
    Route::get('/', [ComplaintController::class, 'index'])->name('index');
    Route::post('/', [ComplaintController::class, 'store'])->name('store');
});

Route::middleware(['auth', 'role:admin'])->get('/reclamations', [ComplaintController::class, 'index'])->name('admin.claims');

Route::middleware(['auth', 'role:producer,distributor,admin'])->prefix('mes-retraits')->name('withdrawals.')->group(function () {
    Route::get('/', [WithdrawalRequestController::class, 'index'])->name('index');
    Route::post('/', [WithdrawalRequestController::class, 'store'])->name('store');
});

Route::middleware(['auth', 'role:distributor'])->prefix('distributeur')->name('distributor.')->group(function () {
    Route::get('/dashboard', [DistributorDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('super-admin')->name('super-admin.')->middleware(['auth', 'super_admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/produits', [ProductCatalogController::class, 'index'])->name('products');
    Route::get('/commandes', [SuperAdminOrderController::class, 'index'])->name('orders');
    Route::get('/reclamations', [SuperAdminComplaintController::class, 'index'])->name('claims');
    Route::post('/reclamations/{complaint}/statut', [SuperAdminComplaintController::class, 'update'])->name('claims.update');
    Route::get('/retraits', [SuperAdminWithdrawalController::class, 'index'])->name('withdrawals');
    Route::post('/retraits/{withdrawalRequest}/statut', [SuperAdminWithdrawalController::class, 'update'])->name('withdrawals.update');
    Route::get('/notifications', [SuperAdminNotificationController::class, 'index'])->name('notifications');

    Route::resource('utilisateurs', UserController::class)
        ->parameters(['utilisateurs' => 'user'])
        ->names('users')
        ->except(['show', 'destroy']);
    Route::get('/utilisateurs-archives', [UserController::class, 'archives'])->name('users.archives');
    Route::post('/utilisateurs/{id}/bloquer', [UserController::class, 'bloquer'])->name('users.bloquer');
    Route::post('/utilisateurs/{id}/activer', [UserController::class, 'activer'])->name('users.activer');
    Route::post('/utilisateurs/{id}/archiver', [UserController::class, 'archiver'])->name('users.archiver');
    Route::post('/utilisateurs/{id}/restaurer', [UserController::class, 'restaurer'])->name('users.restaurer');

    Route::get('/administrateurs/export/{format}', [AdminController::class, 'export'])->name('admins.export');
    Route::resource('administrateurs', AdminController::class)
        ->parameters(['administrateurs' => 'admin'])
        ->names('admins')
        ->except(['destroy']);
    Route::post('/administrateurs/{admin}/reinitialiser-mot-de-passe', [AdminController::class, 'resetPassword'])->name('admins.reset-password');
    Route::post('/administrateurs/{admin}/suspendre', [AdminController::class, 'suspend'])->name('admins.suspend');
    Route::post('/administrateurs/{admin}/activer', [AdminController::class, 'activate'])->name('admins.activate');
    Route::post('/administrateurs/{admin}/archiver', [AdminController::class, 'archive'])->name('admins.archive');

    Route::get('/audit', [AuditLogController::class, 'index'])->name('audit.index');
    Route::get('/audit/export/{format}', [AuditLogController::class, 'export'])->name('audit.export');

    Route::get('/localisations', [MapController::class, 'index'])->name('locations');

    Route::get('/2fa/configuration', [TwoFactorController::class, 'setup'])->name('two-factor.setup');
    Route::post('/2fa/configuration', [TwoFactorController::class, 'confirm'])->name('two-factor.confirm');
    Route::post('/2fa/desactiver', [TwoFactorController::class, 'disable'])->name('two-factor.disable');

    $sections = [
        'producers' => 'producteurs',
        'distributors' => 'distributeurs',
        'settings' => 'parametres',
    ];

    foreach ($sections as $section => $path) {
        if ($section === 'settings') {
            Route::get($path, [\App\Http\Controllers\SettingsController::class, 'edit'])
                ->name($section);
        } else {
            Route::get($path, [PlaceholderController::class, 'show'])
                ->defaults('section', $section)
                ->name($section);
        }
    }
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DataTabelController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SimController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'home'])->name('home');

Route::group(['middleware' => ['guest']], function () {
    Route::get('auth/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('auth/register', [AuthController::class, 'register'])->name('auth.register');
    Route::get('auth/forgot-password', [AuthController::class, 'forgot_password'])->name('auth.forgot-password');
    Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login.action');
    Route::post('auth/register', [AuthController::class, 'register'])->name('auth.register.action');
    Route::post('auth/forgot-password', [AuthController::class, 'forgot_password'])->name('auth.forgot-password.action');
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::get('users', [DataTabelController::class, 'users'])->name('users');
    Route::get('clients', [DataTabelController::class, 'clients'])->name('clients');
    Route::get('datatabels', [DataTabelController::class, 'datatabels'])->name('datatabels');
    Route::get('stations', [DataTabelController::class, 'stations'])->name('stations');
    Route::get('sims', [DataTabelController::class, 'sims'])->name('sims');
    Route::get('transactions', [DataTabelController::class, 'transactions'])->name('transactions');
    Route::get('languages', [DataTabelController::class, 'languages'])->name('languages');


    Route::get('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('auth/destroy', [AuthController::class, 'destroy'])->name('auth.destroy');

    // Sims
    // Dashboard
    Route::get('/sim/{id}', [SimController::class, 'get'])->name('sim.get');
    Route::post('/sim/create', [SimController::class, 'create'])->name('sim.create');
    Route::delete('/sim/{id}', [SimController::class, 'delete'])->name('sim.delete');
    Route::put('/sim/{id}', [SimController::class, 'update'])->name('sim.update');

    // Clients
    // Dashboard
    Route::get('/user/all', [UserController::class, 'all'])->name('user.all');
    Route::get('/user/{id}', [UserController::class, 'get'])->name('user.get');
    Route::post('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::delete('/user/{id}', [UserController::class, 'delete'])->name('user.delete');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');

    Route::post('/language', [LanguageController::class, 'create'])->name('language.create');
    Route::get('/language/{word}', [LanguageController::class, 'get'])->name('language.get');
    Route::put('/language/{word}', [LanguageController::class, 'update'])->name('language.update');
    Route::delete('/language/{word}', [LanguageController::class, 'destroy'])->name('language.destroy');


    Route::get('settings/website', [SettingController::class, 'get_website'])->name('settings.website.get');
    Route::get('settings/account', [SettingController::class, 'get_account'])->name('settings.account.get');
    Route::get('settings/pages', [SettingController::class, 'get_pages'])->name('settings.pages.get');

    Route::post('settings/account/update', [SettingController::class, 'update_account'])->name('settings.account.update');

    Route::post('settings/account/upload/image', [SettingController::class, 'upload_image'])->name('settings.account.uploadImage');


    Route::get('/change-language/{locale}', [LanguageController::class, 'change'])->name('change.language');

});

Route::get('/ddd', function () {
    // Clear cache
    Artisan::call('cache:clear');
    // Clear configuration cache
    Artisan::call('config:cache');
    // Clear configuration
    Artisan::call('config:clear');
    // Clear routes
    Artisan::call('route:clear');
    // Cache routes
    Artisan::call('route:cache');
    // Cache views
    Artisan::call('view:cache');
    // Clear views
    Artisan::call('view:clear');
    // back to past page
    return 'ok';
});

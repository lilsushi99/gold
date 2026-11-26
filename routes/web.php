<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin (CMS) routes - everything lives under /admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'show'])->name('login');
        Route::post('login', [LoginController::class, 'store'])->name('login.store');
        Route::get('forgot-password', [PasswordController::class, 'request'])->name('password.request');
        Route::post('forgot-password', [PasswordController::class, 'email'])->name('password.email');
        Route::get('reset-password/{token}', [PasswordController::class, 'reset'])->name('password.reset');
        Route::post('reset-password', [PasswordController::class, 'resetStore'])->name('password.update.guest');
    });

    Route::middleware(['auth', 'password.changed'])->group(function () {
        Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
        Route::get('password', [PasswordController::class, 'edit'])->name('password.edit');
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

        Route::get('/', [DashboardController::class, 'index'])
            ->middleware('permission:dashboard.view')->name('dashboard');
    });
});

/*
|--------------------------------------------------------------------------
| Public site - every page renders through the CMS (pages + sections)
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '^(?!admin(/|$))[A-Za-z0-9_\-/]+$')
    ->name('page');

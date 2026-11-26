<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\DashboardController;
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

// Temporary public landing (replaced by the Blade-converted site in Phase 4).
Route::get('/', fn () => response('Orisa CMS - public site coming in Phase 4. <a href="/admin">Admin</a>'));

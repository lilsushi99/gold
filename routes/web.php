<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\SectionController;
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

        // Pages (draft / preview / publish workflow)
        Route::middleware('permission:pages.manage')->group(function () {
            Route::get('pages', [AdminPageController::class, 'index'])->name('pages.index');
            Route::get('pages/create', [AdminPageController::class, 'create'])->name('pages.create');
            Route::post('pages', [AdminPageController::class, 'store'])->name('pages.store');
            Route::get('pages/{page}', [AdminPageController::class, 'edit'])->name('pages.edit');
            Route::put('pages/{page}', [AdminPageController::class, 'update'])->name('pages.update');
            Route::put('pages/{page}/visibility', [AdminPageController::class, 'visibility'])->name('pages.visibility');
            Route::post('pages/{page}/publish', [AdminPageController::class, 'publish'])->name('pages.publish');
            Route::post('pages/{page}/discard', [AdminPageController::class, 'discard'])->name('pages.discard');
            Route::get('pages/{page}/preview', [AdminPageController::class, 'preview'])->name('pages.preview');
            Route::post('pages/{page}/duplicate', [AdminPageController::class, 'duplicate'])->name('pages.duplicate');
            Route::delete('pages/{page}', [AdminPageController::class, 'destroy'])->name('pages.destroy');
            Route::post('pages/{page}/revisions/{revision}/restore', [AdminPageController::class, 'restore'])->name('pages.restore');
        });
        Route::middleware('permission:sections.manage')->group(function () {
            Route::post('pages/{page}/sections', [SectionController::class, 'store'])->name('sections.store');
            Route::get('pages/{page}/sections/{uid}', [SectionController::class, 'edit'])->name('sections.edit');
            Route::put('pages/{page}/sections/{uid}', [SectionController::class, 'update'])->name('sections.update');
            Route::post('pages/{page}/sections/{uid}/toggle', [SectionController::class, 'toggle'])->name('sections.toggle');
            Route::post('pages/{page}/sections/{uid}/move', [SectionController::class, 'move'])->name('sections.move');
            Route::post('pages/{page}/sections/{uid}/duplicate', [SectionController::class, 'duplicate'])->name('sections.duplicate');
            Route::delete('pages/{page}/sections/{uid}', [SectionController::class, 'destroy'])->name('sections.destroy');
        });

        // Media library (the picker feed is available to anyone who edits content)
        Route::get('media/picker', [MediaController::class, 'picker'])
            ->middleware('permission:media.manage,pages.manage,sections.manage,posts.manage,content.manage,seo.manage')->name('media.picker');
        Route::middleware('permission:media.manage')->group(function () {
            Route::get('media', [MediaController::class, 'index'])->name('media.index');
            Route::post('media', [MediaController::class, 'store'])->name('media.store');
            Route::get('media/{media}', [MediaController::class, 'edit'])->name('media.edit');
            Route::put('media/{media}', [MediaController::class, 'update'])->name('media.update');
            Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
        });
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

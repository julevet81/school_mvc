<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::get('/', function () {
    return view('welcome');
});




Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth']
    ],
    function () {
        Route::get('/dashboard', function () {
            return view('dashboard.dashboard');
        })->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::prefix('schools')->name('schools.')->group(
            function (): void {

                Route::get('/',              [SchoolController::class, 'index'])->name('index');
                Route::get('/create',        [SchoolController::class, 'create'])->name('create');
                Route::post('/',             [SchoolController::class, 'store'])->name('store');
                Route::get('/{school}',      [SchoolController::class, 'show'])->name('show');
                Route::get('/{school}/edit', [SchoolController::class, 'edit'])->name('edit');
                Route::put('/{school}',      [SchoolController::class, 'update'])->name('update');
                Route::delete('/{school}',   [SchoolController::class, 'destroy'])->name('destroy');

                // إجراءات إضافية
                Route::post('/{id}/restore',         [SchoolController::class, 'restore'])->name('restore');
                Route::patch('/{school}/toggle-active', [SchoolController::class, 'toggleActive'])->name('toggle-active');

                // ── Branches (nested) ─────────────────────────────────
                Route::prefix('/{school}/branches')->name('branches.')->group(function (): void {

                    Route::get('/',               [BranchController::class, 'index'])->name('index');
                    Route::get('/create',         [BranchController::class, 'create'])->name('create');
                    Route::post('/',              [BranchController::class, 'store'])->name('store');
                    Route::get('/{branch}',       [BranchController::class, 'show'])->name('show');
                    Route::get('/{branch}/edit',  [BranchController::class, 'edit'])->name('edit');
                    Route::put('/{branch}',       [BranchController::class, 'update'])->name('update');
                    Route::delete('/{branch}',    [BranchController::class, 'destroy'])->name('destroy');

                    // إجراءات إضافية
                    Route::post('/{id}/restore',              [BranchController::class, 'restore'])->name('restore');
                    Route::patch('/{branch}/set-main',        [BranchController::class, 'setAsMain'])->name('set-main');
                    Route::patch('/{branch}/toggle-active',   [BranchController::class, 'toggleActive'])->name('toggle-active');
                });
            }
        );

    }
);

require __DIR__ . '/auth.php';

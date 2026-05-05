<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserAccessController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth'],
    ],
    function (): void {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::resource('schools', SchoolController::class)->except(['show']);

        Route::prefix('schools')->name('schools.')->group(function (): void {
            Route::prefix('/{school}/branches')->name('branches.')->group(function (): void {
                Route::get('/', [BranchController::class, 'index'])->name('index');
                Route::get('/create', [BranchController::class, 'create'])->name('create');
                Route::post('/', [BranchController::class, 'store'])->name('store');
                Route::get('/{branch}/edit', [BranchController::class, 'edit'])->name('edit');
                Route::put('/{branch}', [BranchController::class, 'update'])->name('update');
                Route::delete('/{branch}', [BranchController::class, 'destroy'])->name('destroy');
            });
        });

        Route::resource('academic-years', AcademicYearController::class)->except(['show']);
        Route::resource('grades', GradeController::class)->except(['show']);
        Route::resource('classrooms', ClassroomController::class)->except(['show']);
        Route::resource('students', StudentController::class)->except(['show']);
        Route::resource('employees', EmployeeController::class)->except(['show']);
        Route::resource('fees', FeeController::class)->except(['show']);
        Route::resource('invoices', InvoiceController::class)->except(['show']);
        Route::resource('attendances', AttendanceController::class)->except(['show', 'destroy']);
        Route::post('attendances/{attendance}/notify-absences', [AttendanceController::class, 'notifyAbsences'])->name('attendances.notify-absences');
        Route::get('attendances/{attendance}/print', [AttendanceController::class, 'print'])->name('attendances.print');
        Route::get('attendance-reports', [AttendanceReportController::class, 'index'])->name('attendance-reports.index');
        Route::get('attendance-reports/print', [AttendanceReportController::class, 'print'])->name('attendance-reports.print');
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('permissions', PermissionController::class)->except(['show']);
        Route::get('user-access', [UserAccessController::class, 'index'])->name('user-access.index');
        Route::get('user-access/{user}/edit', [UserAccessController::class, 'edit'])->name('user-access.edit');
        Route::put('user-access/{user}', [UserAccessController::class, 'update'])->name('user-access.update');
    }
);

require __DIR__.'/auth.php';

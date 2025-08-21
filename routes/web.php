<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

// Dashboard (role-based)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/home', [DashboardController::class, 'index'])->name('home');

// Custom route for easier access to file manager
Route::middleware(['auth'])->group(function () {
    Route::get('/file-manager', function () {
        return view('filemanager.index');
    })->name('filemanager');

    // Also add the direct filemanager route
    Route::get('/filemanager', function () {
        return view('filemanager.index');
    })->name('filemanager.index');
});

// Student Management Routes (Admin only)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
    Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::get('/students/{id}/change-password', [StudentController::class, 'changePasswordForm'])->name('students.change-password-form');
    Route::put('/students/{id}/change-password', [StudentController::class, 'changePassword'])->name('students.change-password');
    Route::get('/students/{id}/delete', [StudentController::class, 'deleteConfirm'])->name('students.delete-confirm');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
});

// Admin Management Routes (Admin only)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/create', [AdminController::class, 'create'])->name('create');
    Route::post('/', [AdminController::class, 'store'])->name('store');
    Route::get('/{id}/change-password', [AdminController::class, 'changePasswordForm'])->name('change-password-form');
    Route::put('/{id}/change-password', [AdminController::class, 'changePassword'])->name('change-password');
});

// Attendance Management Routes (Admin only)
Route::middleware(['auth', 'admin'])->prefix('attendance')->name('attendance.')->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('index');
    Route::get('/create', [AttendanceController::class, 'create'])->name('create');
    Route::post('/store', [AttendanceController::class, 'store'])->name('store');
    Route::get('/statistics', [AttendanceController::class, 'statistics'])->name('statistics');
});

// Blog/Post Routes
Route::middleware('auth')->group(function () {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

    // Admin only post management
    Route::middleware('admin')->group(function () {
        Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
        Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::get('/posts-manage', [PostController::class, 'manage'])->name('posts.manage');
    });
});




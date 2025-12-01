<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RFIDController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('auth.login');
});

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // User Management
    Route::get('/users', [AdminController::class, 'userIndex'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'userCreate'])->name('users.create');
    Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'userEdit'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'userDelete'])->name('users.delete');

    // Attendance Management
    Route::get('/attendance/history', [AdminController::class, 'attendanceHistory'])->name('attendance.history');
    Route::get('/attendance/manual', [AdminController::class, 'attendanceManualForm'])->name('attendance.manual');
    Route::post('/attendance/manual', [AdminController::class, 'attendanceManualStore'])->name('attendance.manual.store');
    Route::post('/attendance/check-in', [AdminController::class, 'quickCheckIn'])->name('attendance.checkIn');
    Route::post('/attendance/check-out', [AdminController::class, 'quickCheckOut'])->name('attendance.checkOut');
});

// RFID & Attendance Routes
Route::get('/scan', [RFIDController::class, 'scanView'])->name('attendance.scan');
Route::post('/scan', [RFIDController::class, 'scan'])->name('attendance.scan.post');
Route::get('/attendance/settings', [RFIDController::class, 'settings'])->name('attendance.settings');
Route::post('/attendance/settings', [RFIDController::class, 'updateSettings'])->name('attendance.settings.update');

Route::get('/history', [RFIDController::class, 'history'])->name('attendance.history.user');
Route::get('/user', [RFIDController::class, 'user'])->name('attendance.user');

// Legacy user routes (dapat dihapus jika tidak digunakan lagi)
Route::get('/user/create', [RFIDController::class, 'createUser'])->name('rfid.users.create');
Route::post('/user/store', [RFIDController::class, 'storeUser'])->name('rfid.users.store');
Route::delete('/user/delete/{id}', [RFIDController::class, 'deleteUser'])->name('rfid.users.delete');
Route::get('/user/edit/{id}', [RFIDController::class, 'editUser'])->name('rfid.users.edit');
Route::post('/user/update/{id}', [RFIDController::class, 'updateUser'])->name('rfid.users.update');

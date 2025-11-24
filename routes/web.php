<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RFIDController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/scan', [RFIDController::class, 'scanView'])->name('attendance.scan');
Route::post('/scan', [RFIDController::class, 'scan'])->name('attendance.scan.post');
Route::get('/attendance/settings', [RFIDController::class, 'settings'])->name('attendance.settings');
Route::post('/attendance/settings', [RFIDController::class, 'updateSettings'])->name('attendance.settings.update');
Route::resource('users', UserController::class);

Route::get('/history', [RFIDController::class, 'history'])->name('attendance.history');

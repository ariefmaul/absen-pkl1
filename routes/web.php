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

Route::get('/history', [RFIDController::class, 'history'])->name('attendance.history');
// user route
Route::get('/user', [RFIDController::class, 'user'])->name('attendance.user');
//create
Route::get('/user/create', [RFIDController::class, 'createUser'])->name('users.create');
// store
Route::post('/user/store', [RFIDController::class, 'storeUser'])->name('users.store');
//delete
Route::delete('/user/delete/{id}', [RFIDController::class, 'deleteUser'])->name('users.delete');

// edit
Route::get('/user/edit/{id}', [RFIDController::class, 'editUser'])->name('users.edit');
// update
Route::post('/user/update/{id}', [RFIDController::class, 'updateUser'])->name('users.update');

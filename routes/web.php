<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\RekeningController;


Route::get('/', function () {
    return view('welcome');
});


// Dashboard
Route::get('/dashboardnew', [DashboardController::class, 'index'])->name('dashboard');

// Costumer
Route::get('/masterdata/costumer', [CostumerController::class, 'index'])->name('costumer');

// Driver
Route::get('/masterdata/driver', [DriverController::class, 'index'])->name('driver');

// Rekening
Route::get('/masterdata/rekening', [RekeningController::class, 'index'])->name('rekening');

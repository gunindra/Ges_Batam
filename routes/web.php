<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\RekeningController;


Route::get('/', function () {
    return view('welcome');
});

// Login
Route::get('/login', [LoginController::class, 'index'])->name('login');

// Dashboard
Route::get('/dashboardnew', [DashboardController::class, 'index'])->name('dashboard');

// Booking Confirmation
Route::get('/booking', [BookingController::class, 'index'])->name('booking');

// Delivery
Route::get('/delivery', [DeliveryController::class, 'index'])->name('delivery');

// Costumer
Route::get('/masterdata/costumer', [CostumerController::class, 'index'])->name('costumer');
Route::get('/masterdata/costumer/list', [CostumerController::class, 'getlistCostumer'])->name('getlistCostumer');
Route::post('/masterdata/costumer/tambah', [CostumerController::class, 'addCostumer'])->name('addCostumer');
Route::post('/masterdata/costumer/update', [CostumerController::class, 'updateCostumer'])->name('updateCostumer');
Route::get('/masterdata/destroy', [CostumerController::class, 'destroyCostumer'])->name('destroyCostumer');

// Driver
Route::get('/masterdata/driver', [DriverController::class, 'index'])->name('driver');

// Rekening
Route::get('/masterdata/rekening', [RekeningController::class, 'index'])->name('rekening');

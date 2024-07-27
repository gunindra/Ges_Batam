<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\CostumerController;

Route::get('/', function () {
    return view('welcome');
});


// Dashboard
Route::get('/dashboardnew', [DashboardController::class, 'index'])->name('dashboard');

// Master Data
Route::get('/masterdata', [MasterController::class, 'index'])->name('masterdata');

// Costumer
Route::get('/masterdata/costumer', [CostumerController::class, 'index'])->name('costumer');



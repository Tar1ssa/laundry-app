<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransController;

Route::get('/', function () {
    return view('welcome');
});


Route::resource('dashboard', DashboardController::class);

// Master Data
Route::resource('user', UserController::class);
Route::resource('level', LevelController::class);
Route::resource('service', ServiceController::class);
Route::resource('customer', CustomerController::class);
// End Master Data

// Transaction
Route::resource('transaksi', TransController::class);

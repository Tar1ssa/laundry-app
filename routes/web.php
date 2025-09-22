<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PickupController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [LoginController::class, 'login'])->name('login');
Route::post('actionLogin', [LoginController::class, 'actionLogin'])->name('actionLogin');

Route::middleware('auth')->group(function () {

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::resource('dashboard', DashboardController::class);

    // Administrator
    Route::middleware('role:Administrator')->group(function () {
        // Master Data
        Route::resource('user', UserController::class);
        Route::resource('level', LevelController::class);
        Route::resource('service', ServiceController::class);
        Route::resource('customer', CustomerController::class);
        // End Master Data
    });

    Route::middleware('role:Operator')->group(function () {
        // Transaction
        Route::resource('transaksi', TransController::class);
        Route::put('transaksi.done/{id}', [TransController::class, 'done'])->name('transaksi.done');
        // End Transaction

        // Pickup
        Route::resource('pickup', PickupController::class);
        Route::post('ready', [PickupController::class, 'ready'])->name('pickup.ready');
    });
});

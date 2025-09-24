<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
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
        Route::get('laundry.transc', [TransController::class, 'laundryTrans'])->name('laundry.transc');
        Route::get('get-customer/{id}', [TransController::class, 'getCustomerDataById']);
        Route::get('get-layanan', [TransController::class, 'getLayanan']);
        Route::get('get-transcode', [TransController::class, 'getTranscode']);
        Route::post('/transaksi/store', [TransController::class, 'LaundryStore'])->name('laundry.store');
        Route::get('get-transaksi', [TransController::class, 'getTransaksi'])->name('getTransaksi');
        Route::put('/transaksi/{id}/status', [TransController::class, 'setStatus'])->name('setTransaksiStatus');
        Route::get('get-list-layanan', [TransController::class, 'listLayanan']);


        // End Transaction

        // Pickup
        Route::resource('pickup', PickupController::class);
        Route::post('ready', [PickupController::class, 'ready'])->name('pickup.ready');
    });

    Route::middleware('role:Pimpinan')->group(function () {
        Route::resource('laporan', LaporanController::class);
    });
});

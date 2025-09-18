<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ServiceController;

Route::get('/', function () {
    return view('welcome');
});


Route::resource('dashboard', DashboardController::class);
Route::resource('user', UserController::class);
Route::resource('level', LevelController::class);
Route::resource('service', ServiceController::class);

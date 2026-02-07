<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\RequestMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(RequestMiddleware::class)
    ->resource('users', UserController::class);

<?php

use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

//Route::middleware('auth')
//    ->resource('users', UserController::class);
//


Route::resource('users', UserController::class)->except('edit');
Route::get('users/{slug}/edit', [UserController::class, 'edit'])->name('users.edit');


Auth::routes();

Route::get('/home', [\App\Http\Controllers\Web\HomeController::class, 'index'])->name('home');

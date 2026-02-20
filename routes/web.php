<?php

use App\Http\Controllers\MusicController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

//Route::middleware('auth')
//    ->resource('users', UserController::class);
//


// USER CONTROLLER
Route::resource('users', UserController::class)->except('edit');
Route::get('users/{slug}/edit', [UserController::class, 'edit'])->name('users.edit');

// MUSIC CONTROLLER
Route::prefix('music')->name('music.')->group(function () {
    Route::get('', [MusicController::class, 'index'])->name('index');
    Route::post('save/favorite/{music}', [MusicController::class, 'saveFavorite'])->name('save.favorite');
});

Auth::routes();

Route::get('/home', [\App\Http\Controllers\Web\HomeController::class, 'index'])->name('home');

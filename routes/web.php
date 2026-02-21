<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::middleware('auth')->group(function(){
    // MUSIC CONTROLLER
    Route::name('music.')->group(function () {
        Route::get('', [MusicController::class, 'index'])->name('index');
        Route::post('save/favorite/{music}', [MusicController::class, 'saveFavorite'])->name('save.favorite');
        Route::post('track/listen-progress', [MusicController::class, 'trackListenProgress'])->name('track.listen_progress');
    });

    // USER CONTROLLER
    Route::resource('users', UserController::class)->except('edit');
    Route::get('users/{slug}/edit', [UserController::class, 'edit'])->name('users.edit');

    // FAVORITE CONTROLLER
    Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites.index');
});

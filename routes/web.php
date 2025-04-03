<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\FavoriteMovieController;

Route::get('login', [AuthController::class, 'loginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [MovieController::class, 'index'])->name('movies.index');
    Route::get('movies/{id}', [MovieController::class, 'show'])->name('movies.show');
    Route::post('favorites', [FavoriteMovieController::class, 'store'])->name('favorites.store');
    Route::delete('favorites/{id}', [FavoriteMovieController::class, 'destroy'])->name('favorites.destroy');
    Route::get('favorites', [FavoriteMovieController::class, 'index'])->name('favorites.index');
});

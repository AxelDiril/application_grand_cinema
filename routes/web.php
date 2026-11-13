<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ScreeningController;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::get('/movies_list', [MovieController::class, 'getMovieList']);
Route::get('/screenings_list', [ScreeningController::class, 'getScreeningsList']);
Route::get('/movie/{movie_id}', [MovieController::class, 'getMovie']);

require __DIR__.'/settings.php';

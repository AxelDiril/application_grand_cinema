<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\ReservationController;

Route::get('/', function () {
    return view('pages.accueil');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    return view('pages.accueil');
});

Route::get('/movies_list', [MovieController::class, 'getMovieList']);
Route::get('/screenings_list', [ScreeningController::class, 'getScreeningsList']);
Route::get('/movie/{movie_id}', [MovieController::class, 'getMovie']);
Route::get('/add_screening', [ScreeningController::class, 'addScreening']);
Route::post('/insert_screening', [ScreeningController::class, 'insertScreening']);
Route::get('/edit_screening/{screening_id}', [ScreeningController::class, 'editScreening']);
Route::post('/update_screening/{screening_id}', [ScreeningController::class, 'updateScreening']);
Route::get('/reservations_list', [ReservationController::class, 'getReservationsList']);
Route::get('/make_reservation/{screening_id}', [ScreeningController::class, 'makeReservation']);
Route::post('/confirm_reservation/{screening_id}', [ScreeningController::class, 'confirmReservation']);
Route::post('/add_reservation/{screening_id}', [ReservationController::class, 'addReservation']);
Route::get('/my_tickets', [ReservationController::class, 'getMyTickets']);
Route::get('/download_ticket/{reservation_id}', [ReservationController::class, 'downloadTicket']);
Route::get('/admin_dashboard', [ScreeningController::class, 'getAdminDashboard']);

require __DIR__.'/settings.php';

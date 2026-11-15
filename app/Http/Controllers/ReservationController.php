<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    
    /**
     * Récupère la liste des réservations avec le nom de l'utilisateur,
     * le film, la date de séance, la salle et le nombre de places réservées.
     * Des filtres peuvent être appliqués par nom, identifiant de réservation,
     * date, film et salle.
     *
     * @param Request $request Les paramètres de filtres de la requête.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse La vue affichant les 
     * réservations ou un retour sur la page d'accueil si l'utilisateur n'a pas le rôle requis.
     */
    public function getReservationsList(Request $request)
    {

        // Vérifie que l'utilisateur soit connecté et possède le rôle d'administrateur ou
        // d'agent de contrôle.
        $objAuthUser = Auth::user();

        if (!$objAuthUser || $objAuthUser->role_code != 'A') {
            return redirect('/');
        }

        
        $strName = $request->query('name');
        $iReservationId = $request->query('reservation_id');
        $strDate = $request->query('screening_date');
        $iMovieId = $request->query('movie_id');
        $iRoomId = $request->query('room_id');

        $arReservations = Reservation::with([
            'user',
            'screening.movie',
            'screening.room'
        ]);

        // Filtre par nom de l'utilisateur
        if (!empty($strName) && $strName != "all") {
            $arReservations->whereHas('user', function ($query) use ($strName) {
                $query->where('name', 'LIKE', '%' . $strName . '%');
            });
        }

        // Filtre par identifiant de réservation
        if (!empty($iReservationId)) {
            $arReservations->where('reservation_id', $iReservationId);
        }

        // Filtre par date de séance
        if (!empty($strDate)) {
            $arReservations->whereHas('screening', function ($query) use ($strDate) {
                $query->whereDate('screening_date', $strDate);
            });
        }

        // Filtre par film
        if (!empty($iMovieId) && $iMovieId != "all") {
            $arReservations->whereHas('screening', function ($query) use ($iMovieId) {
                $query->where('movie_id', $iMovieId);
            });
        }

        // Filtre par salle
        if (!empty($iRoomId) && $iRoomId != "all") {
            $arReservations->whereHas('screening', function ($query) use ($iRoomId) {
                $query->where('room_id', $iRoomId);
            });
        }

        // Récupération des réservations après application des filtres
        $arReservations = $arReservations
            ->orderBy('reservation_id', 'asc')
            ->get();

        // Récupération des films et des salles pour les filtres
        $arMovies = Movie::get();
        $arRooms = Room::get();

        return view('pages/reservations_list', compact(
            'arReservations',
            'arMovies',
            'arRooms',
            'strName',
            'iReservationId',
            'strDate',
            'iMovieId',
            'iRoomId'
        ));
    }

}

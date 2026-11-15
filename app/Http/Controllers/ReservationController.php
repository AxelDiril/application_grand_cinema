<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Screening;
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

        if (!$objAuthUser || ($objAuthUser->role_code != 'A' && $objAuthUser->role_code != 'C')) {
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

    /**
     * Enregistre la réservation de l'utilisateur en base de données.
     *
     * @param Request $request Les données du formulaire.
     * @param int $screening_id Identifiant de la séance.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Retourne un message de
     * confirmation à l'utilisateur ou renvoie l'utilisateur non-connecté vers la page d'accueil.
     */
    public function addReservation(Request $request, $screening_id)
    {
        // Vérifie que l'utilisateur est connecté.
        $objAuthUser = Auth::user();

        if (!$objAuthUser) {
            return redirect('/');
        }

        // Récupère la séance et sa salle.
        $objScreening = Screening::with('room')->find($screening_id);

        if (!$objScreening) {
            return redirect('/');
        }

        // Vérifie le nombre de places demandé.
        $arData = $request->validate([
            'seats_quantity' => 'required|integer|min:1'
        ]);

        $iSeatsQuantity = $arData['seats_quantity'];

        // Calcule le nombre de places disponibles.
        $iReservedSeats = $objScreening->reservations()
            ->sum('seats_quantity');

        $iAvailableSeats = $objScreening->room->capacity - $iReservedSeats;

        if ($iSeatsQuantity > $iAvailableSeats) {
            return redirect('/make_reservation/' . $screening_id);
        }

        // Crée la réservation.
        Reservation::create([
            'screening_id' => $screening_id,
            'id' => $objAuthUser->id,
            'seats_quantity' => $iSeatsQuantity
        ]);

        // Redirige vers un message de confirmation.
        $strMessage = "Votre réservation a bien été enregistrée.";
        $strLink = "/";
        $strLinkMessage = "Retour à l'accueil";

        return view('pages/message', compact(
            'strMessage',
            'strLink',
            'strLinkMessage'
        ));
    }
}

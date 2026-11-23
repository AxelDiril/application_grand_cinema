<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Screening;
use App\Models\Room;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Seat;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScreeningController extends Controller
{
    /**
     * Récupère la liste des séances et les affiche avec le nom du film, la salle où ils seront
     * diffusés et leur nombre de places disponibles.
     * Des filtres peuvent être appliqués par nom, genre, salle et date pour affiner la recherche.
     *
     * @param Request $request Les paramètres de filtres de la requête.
     * @return \Illuminate\View\View La vue affichant les séances.
     */
    public function getScreeningsList(Request $request)
    {
        $strName = $request->query('movie_name');
        $iGenreId = $request->query('genre_id');
        $iRoomId = $request->query('room_id');
        $strDate = $request->query('screening_date');

        $arScreenings = Screening::with([
            'movie',
            'room' => function ($query) {
                $query->withCount('seats');
            }
        ])
            ->withCount('reservations');

        // Filtre par nom du film
        if (!empty($strName) && $strName != "all") {
            $arScreenings->whereHas('movie', function ($query) use ($strName) {
                $query->where('movie_name', 'LIKE', '%' . $strName . '%');
            });
        }

        // Filtre par genre
        if (!empty($iGenreId) && $iGenreId != "all") {
            $arScreenings->whereHas('movie.genres', function ($query) use ($iGenreId) {
                $query->where('genres.genre_id', $iGenreId);
            });
        }

        // Filtre par salle
        if (!empty($iRoomId) && $iRoomId != "all") {
            $arScreenings->where('room_id', $iRoomId);
        }

        // Filtre par date
        if (!empty($strDate)) {
            $arScreenings->whereDate('screening_date', $strDate);
        }

        // Récupération des séances après application des filtres
        $arScreenings = $arScreenings
            ->orderBy('screening_date', 'asc')
            ->get();

        // Calcul des places disponibles pour chaque séance
        foreach ($arScreenings as $screening) {
            $screening->available_seats =
                $screening->room->seats_count
                - $screening->reservations_count;
        }

        $arRooms = Room::get();
        $arGenres = Genre::get();

        return view('pages/screenings_list', compact(
            'arScreenings',
            'arRooms',
            'arGenres',
            'strName',
            'iGenreId',
            'iRoomId',
            'strDate'
        ));
    }

    /**
     * Récupère la liste des films et des salles pour qu'un administrateur connecté puisse ajouter
     * une séance à la programmation à travers un formulaire.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Retourne le formulaire
     * d'ajout de séances ou redirige l'utilisateur non-administrateur ou non connecté vers la
     * page d'accueil.
     **/
    public function addScreening()
    {
        // Vérifier si l'utilisateur est administrateur
        $objAuthUser = Auth::user();
        if ($objAuthUser == false || $objAuthUser->role_code != 'A') {
            return redirect('/');
        }

        $arRooms = Room::get();
        $arMovies = Movie::get();

        return view('pages/add_screening', compact(
            'arRooms',
            'arMovies'
        ));
    }

        /**
     * Récupère les données du formulaire d'ajout de séances, vérifie si un scéance n'existe pas
     * déjà pour la salle et l'horaire données et enregistre la séance en base de données.
     * @param Request $request Les données du formulaire.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Retourne le formulaire
     * d'ajout de séances ou redirige l'utilisateur non-administrateur ou non connecté vers la
     * page d'accueil.
     **/
    public function insertScreening(Request $request)
    {
        // Vérifier que l'utilisateur est administrateur
        $objAuthUser = Auth::user();

        if (!$objAuthUser || $objAuthUser->role_code != 'A') {
            return redirect('/');
        }

        // Valider les données du formulaire
        $arData = $request->validate([
            'movie_id' => 'required|exists:movies,movie_id',
            'room_id' => 'required|exists:rooms,room_id',
            'screening_date' => 'required|date_format:Y-m-d\TH:i',
        ]);

        // Convertir la date reçue
        $objDate = Carbon::parse($arData['screening_date']);

        // Vérifier si une séance existe déjà avec la même salle et même horaire
        $bScreeningExists = Screening::where('room_id', $arData['room_id'])
            ->where('screening_date', '>=', $objDate->copy()->startOfMinute())
            ->where('screening_date', '<', $objDate->copy()->addMinute())
            ->exists();

        if ($bScreeningExists) {
            $strMessage = "Une séance existe déjà pour cette salle et cet horaire.";
            $strLink = "/add_screening";
            $strLinkMessage = "Retour à l'ajout d'une séance.";
        }
        else {
            // Créer la séance uniquement si elle n'existe pas déjà
            $objScreening = new Screening();
            $objScreening->movie_id = $arData['movie_id'];
            $objScreening->room_id = $arData['room_id'];
            $objScreening->screening_date = $objDate;
            $objScreening->save();

            $strMessage = "La séance a bien été créée.";
            $strLink = "/screenings_list";
            $strLinkMessage = "Retour à la liste des séances.";
        }

        return view('pages/message', compact(
            'strMessage',
            'strLink',
            'strLinkMessage'
        ));
    }

    /**
     * Affiche le formulaire de modification d'une séance.
     * Vérifie que l'utilisateur connecté est administrateur.
     *
     * @param int $screening_id Identifiant de la séance à modifier.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function editScreening($screening_id)
    {
        // Vérifier si l'utilisateur est administrateur
        $objAuthUser = Auth::user();

        if (!$objAuthUser || $objAuthUser->role_code != 'A') {
            return redirect('/');
        }

        // Récupérer la séance
        $objScreening = Screening::find($screening_id);

        if (!$objScreening) {
            return redirect('/');
        }

        // Récupérer les salles et les films
        $arRooms = Room::get();
        $arMovies = Movie::get();

        // Récupérer les informations actuelles de la séance
        $iMovieId = $objScreening->movie_id;
        $iRoomId = $objScreening->room_id;
        $strScreeningDate = Carbon::parse($objScreening->screening_date)
            ->format('Y-m-d\TH:i');

        return view('pages/edit_screening', compact(
            'arRooms',
            'arMovies',
            'iMovieId',
            'iRoomId',
            'strScreeningDate',
            'screening_id'
        ));
    }

    /**
     * Récupère les données du formulaire d'édition de séance pour la mettre à jour en base
     * de données.
     *
     * @param Request $request Données du formulaires.
     * @param int $screening_id Identifiant de la séance à modifier.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function updateScreening(Request $request, $screening_id)
    {
        // Vérifier que l'utilisateur est administrateur
        $objAuthUser = Auth::user();

        if (!$objAuthUser || $objAuthUser->role_code != 'A') {
            return redirect('/');
        }

        // Récupérer la séance à modifier
        $objScreening = Screening::find($screening_id);

        if (!$objScreening) {
            return redirect('/');
        }

        // Valider les données du formulaire
        $arData = $request->validate([
            'movie_id' => 'required|exists:movies,movie_id',
            'room_id' => 'required|exists:rooms,room_id',
            'screening_date' => 'required|date_format:Y-m-d\TH:i',
        ]);

        // Convertir la date reçue
        $objDate = Carbon::parse($arData['screening_date']);

        // Vérifier si une autre séance existe déjà dans la même salle
        // à la même date et au même horaire
        $bScreeningExists = Screening::where('room_id', $arData['room_id'])
            ->where('screening_id', '!=', $screening_id)
            ->where('screening_date', '>=', $objDate->copy()->startOfMinute())
            ->where('screening_date', '<', $objDate->copy()->addMinute())
            ->exists();

        if ($bScreeningExists) {
            $strMessage = "Une séance existe déjà pour cette salle et cet horaire.";
            $strLink = "/edit_screening/" . $screening_id;
            $strLinkMessage = "Retour à la modification de la séance.";
        }
        else {
            // Modifier la séance
            $objScreening->movie_id = $arData['movie_id'];
            $objScreening->room_id = $arData['room_id'];
            $objScreening->screening_date = $objDate;
            $objScreening->save();

            $strMessage = "La séance a bien été modifiée.";
            $strLink = "/screenings_list";
            $strLinkMessage = "Retour à la liste des séances.";
        }

        return view('pages/message', compact(
            'strMessage',
            'strLink',
            'strLinkMessage'
        ));
    }

    /**
     * Affiche les détails d'une séance afin que l'utilisateur puisse effectuer une réservation.
     *
     * @param int $screening_id Identifiant de la séance sélectionnée.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Retourne le formulaire
     * de réservation ou redirige l'utilisateur non-connecté vers la page de connexion.
     */
    public function makeReservation($screening_id)
    {
        // Vérifie que l'utilisateur est connecté.
        $objAuthUser = Auth::user();

        if (!$objAuthUser) {
            // Redirige vers un message pour informer qu'un compte est nécessaire.
            $strMessage = "Vous devez vous connecter pour effectuer une réservation.";
            $strLink = "/login";
            $strLinkMessage = "Connexion";

            return view('pages/message', compact(
                'strMessage',
                'strLink',
                'strLinkMessage'
            ));
        }

        // Récupère la séance et ses relations.
        $objScreening = Screening::with(['movie', 'room'])
            ->find($screening_id);

        // Vérifie que la séance existe.
        if (!$objScreening) {
            return redirect('/');
        }

        // Récupère les identifiants des places déjà réservées pour cette séance.
        $arReservedSeatIds = $objScreening->reservations()
            ->pluck('seat_id');

        // Récupère les places de la salle qui ne sont pas encore réservées.
        $arAvailableSeats = Seat::where('room_id', $objScreening->room_id)
            ->whereNotIn('seat_id', $arReservedSeatIds)
            ->orderByRaw('CAST(seat_label AS UNSIGNED) ASC')
            ->get();

        // Calcule le nombre de places disponibles.
        $iAvailableSeats = $arAvailableSeats->count();

        // Affiche la page de réservation.
        return view('pages/make_reservation', compact(
            'objScreening',
            'iAvailableSeats',
            'arAvailableSeats'
        ));
    }

    /**
     * Affiche le récapitulatif d'une réservation avant sa validation.
     *
     * @param Request $request Les données du formulaire.
     * @param int $screening_id Identifiant de la séance sélectionnée.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Retourne le récapitulatif
     * de commande ou redirige l'utilisateur non-connecté vers la page d'accueil.
     */
    public function confirmReservation(Request $request, $screening_id)
    {
        // Vérifie que l'utilisateur est connecté.
        $objAuthUser = Auth::user();

        if (!$objAuthUser) {
            return redirect('/');
        }

        // Récupère la séance et ses relations.
        $objScreening = Screening::with(['movie', 'room'])
            ->find($screening_id);

        // Vérifie que la séance existe.
        if (!$objScreening) {
            return redirect('/');
        }

        // Vérifie que la place sélectionnée existe.
        $arData = $request->validate([
            'seat_id' => 'required|integer|exists:seats,seat_id'
        ]);

        // Récupère la place sélectionnée.
        $objSeat = Seat::where('seat_id', $arData['seat_id'])
            ->where('room_id', $objScreening->room_id)
            ->first();

        // Vérifie que la place appartient bien à la salle de la séance.
        if (!$objSeat) {
            return redirect('/make_reservation/' . $screening_id);
        }

        // Vérifie que la place n'est pas déjà réservée pour cette séance.
        $bSeatReserved = $objScreening->reservations()
            ->where('seat_id', $objSeat->seat_id)
            ->exists();

        if ($bSeatReserved) {
            return redirect('/make_reservation/' . $screening_id);
        }

        // Calcule le prix total (10 € par place).
        $fTotalPrice = 10;

        // Affiche le récapitulatif de la réservation.
        return view('pages/confirm_reservation', compact(
            'objScreening',
            'objSeat',
            'fTotalPrice'
        ));
    }

    /**
     * Affiche le tableau de bord administrateur avec les dernières séances et leur taux de remplissage.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Retourne la liste des séances
     * ou renvoie l'utilisateur à la page d'accueil si il n'est pas administrateur.
     */
    public function getAdminDashboard()
    {
        $objAuthUser = Auth::user();

        // Vérifie que l'utilisateur est administrateur
        if (!$objAuthUser || $objAuthUser->role_code != 'A') {
            return redirect('/');
        }

        // Récupère les séances avec le nombre de places et de réservations
        $arScreenings = Screening::with([
            'movie',
            'room' => function ($query) {
                $query->withCount('seats');
            }
        ])
            ->withCount('reservations')
            ->orderBy('screening_date', 'desc')
            ->get();

        // Calcule les places occupées et le pourcentage de remplissage
        foreach ($arScreenings as $screening) {
            $iOccupiedSeats = $screening->reservations_count ?? 0;
            $iTotalSeats = $screening->room->seats_count ?? 0;

            $screening->occupied_seats = $iOccupiedSeats;
            $screening->occupancy_rate = $iTotalSeats > 0
                ? round(($iOccupiedSeats / $iTotalSeats) * 100, 2)
                : 0;
        }

        return view('pages/admin_dashboard', compact('arScreenings'));
    }

    /**
     * Supprime une séance de la base de données à partir de son Id. Ne peut être effectué que par
     * un administrateur.
     *
     * @param int $screening_id Id de
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Retourne un message de
     * confirmation de la suppression ou renvoie l'utilisateur à la page d'accueil si il n'est pas
     * administrateur.
     */
    public function deleteScreening($screening_id)
    {
        $objAuthUser = Auth::user();

        // Vérification des droits administrateur
        if (!$objAuthUser || $objAuthUser->role_code != 'A') {
            return redirect('/');
        }

        // Recherche de la séance
        $objScreening = Screening::find($screening_id);

        if (!$objScreening) {
            return redirect('/');
        }

        // Suppression de la séance
        $objScreening->delete();

        $strMessage = "La séance a bien été supprimée.";
        $strLink = "/screenings_list";
        $strLinkMessage = "Retour à la liste des séances";

        return view('pages/message', compact(
                'strMessage',
                'strLink',
                'strLinkMessage'
        ));
    }
}

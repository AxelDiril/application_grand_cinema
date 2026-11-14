<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Screening;
use App\Models\Room;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScreeningController extends Controller
{
    /**
     * Récupère la liste des séances et les affiche avec le nom du film, la salle où ils seront
     * diffusés et leur nombre de places disponibles.
     * Des filtres peuvent être appliqués par nom, genre, salle et date pour affiner la recherche.
     *
     * @param Request  $request Les paramètres de filtres de la requête.
     * @return \Illuminate\View\View La vue affichant les détails et séances du film.
     */
    
    public function getScreeningsList(Request $request)
    {
        $strName = $request->query('movie_name');
        $iGenreId = $request->query('genre_id');
        $iRoomId = $request->query('room_id');
        $strDate = $request->query('screening_date');

        $arScreenings = Screening::with(['movie', 'room'])
            ->withSum('reservations', 'seats_quantity');

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
                $screening->room->capacity
                - ($screening->reservations_sum_seats_quantity ?? 0);
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
            $strLinkMessage = "Retour à l'ajout d'une salle.";
        }
        else{
            $strMessage = "La séance a bien été créée.";
            $strLink = "/screenings_list";
            $strLinkMessage = "Retour à la liste des séances.";
        }

        // Créer la séance
        $objScreening = new Screening();
        $objScreening->movie_id = $arData['movie_id'];
        $objScreening->room_id = $arData['room_id'];
        $objScreening->screening_date = $objDate;
        $objScreening->save();

        return view('pages/message', compact(
            'strMessage',
            'strLink',
            'strLinkMessage'
        ));
    }

}

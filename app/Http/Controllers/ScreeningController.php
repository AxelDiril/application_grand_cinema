<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Screening;
use App\Models\Room;
use App\Models\Genre;

class ScreeningController extends Controller
{
    /**
     * Récupère la liste des séances et les affiche avec le nom du film et la salle où ils seront
     * diffusés.
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

        $arScreenings = Screening::with(['movie', 'room']);

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

        $arScreenings = $arScreenings->orderBy('screening_date', 'asc')->get();

        $arRooms = Room::get();
        $arGenres = Genre::get();

        return view('pages/screenings_list', compact('arScreenings','arRooms','arGenres',
        'strName','iGenreId','iRoomId','strDate'));
    }
}

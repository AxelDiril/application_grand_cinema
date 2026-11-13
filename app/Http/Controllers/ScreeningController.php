<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Screening;

class ScreeningController extends Controller
{
    /**
     * Récupère la liste des séances et les affiche avec le nom du film et la salle où ils seront
     * diffusés.
     *
     * @return \Illuminate\View\View La vue affichant les détails et séances du film.
     */
    public function getScreeningsList()
    {
        $arScreenings = Screening::with(['movie', 'room'])->get();

        return view('pages/screenings_list', compact('arScreenings'));
    }
}

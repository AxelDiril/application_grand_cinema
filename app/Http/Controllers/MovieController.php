<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Screening;

class MovieController extends Controller
{
    /**
     * Récupère la liste des films de la table movies pour les envoyer à la vue movie_list.
     *
     * @return \Illuminate\View\View La vue affichant tous les films.
     */
    public function getMovieList()
    {
        
        $arMovies = Movie::query();
        $arMovies = $arMovies->get();

        return view('pages/movies_list', compact('arMovies'));
    }

    /**
     * Récupère les détails d'un film à afficher à partir de son movie_id et les affiche
     * dans la vue /movie/. Renvoie aussi les séances associées de la table screenings.
     *
     * @param  int  $movie_id L'ID du film à afficher dans la vue.
     * @return \Illuminate\View\View La vue affichant les détails et séances du film.
     */
    public function getMovie($movie_id)
    {
        $objMovie = Movie::find($movie_id);

        // Vérifier si le film existe, sinon rediriger vers une page d'erreur
        if($objMovie == null){
            $strMessage = "Ce film n'existe pas.";
            $strLink = "/";
            $strLinkMessage = "Retour à la page d'accueil.";
            
            return view('pages/message', compact('strMessage','strLink','strLinkMessage'));
        }

        $arScreenings = Screening::where('movie_id', $movie_id)
            ->where('screening_date', '>=', now())
            ->orderBy('screening_date', 'asc')
            ->get();

        return view('pages/movie', compact('objMovie','arScreenings'));
    }
}

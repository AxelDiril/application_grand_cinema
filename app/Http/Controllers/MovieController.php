<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getMovieList()
    {
        
        $arMovies = Movie::query();
        $arMovies = $arMovies->get();

        return view('pages/movies_list', compact('arMovies'));
    }

    public function getMovie($movie_id)
    {
        $objMovie = Movie::find($movie_id);

        if($objMovie == null){
            $strMessage = "Ce film n'existe pas.";
            $strLink = "/";
            $strLinkMessage = "Retour à la page d'accueil.";
            
            return view('pages/message', compact('strMessage','strLink','strLinkMessage'));
        }

        return view('pages/movie', compact('objMovie'));
    }
}

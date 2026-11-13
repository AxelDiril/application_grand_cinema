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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Screening;

class ScreeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getScreeningsList()
    {
        $arScreenings = Screening::with('movie');
        $arScreenings = $arScreenings->get();

        return view('pages/screenings_list', compact('arScreenings'));
    }
}

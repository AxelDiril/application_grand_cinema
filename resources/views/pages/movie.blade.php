@extends('layouts/structure')

@section('titre')
    {{ $objMovie->movie_name }}
@stop

@section('contenu')

    <h2>{{ $objMovie->movie_name }}</h2>

    Réalisateur : {{ $objMovie->director }}<br>
    Durée : {{ $objMovie->duration_minutes }} minutes<br>
    Description :
    @if ($objMovie->description == null)
        Non-renseigné
    @else
        {{ $objMovie->description }}
    @endif

    <h2>Prochaines séances :</h2>

    @if ($arScreenings->isEmpty())
        Aucune séance prévue.
    @else
        @foreach($arScreenings as $screening)
            Le {{ $screening->screening_date }} à {{ $screening->room->room_name }}
            <br>
        @endforeach
    @endif

@stop
@extends('layouts.structure')

@section('titre')
    Tableau de bord
@stop

@section('contenu')

    <h1>Tableau de bord administrateur</h1>

    <h2>Occupation des séances</h2>

    @if($arScreenings->isEmpty())

        <p>Aucune séance n'est actuellement enregistrée.</p>

    @else
        @foreach($arScreenings as $screening)
            {{ $screening->movie->movie_name }}
            {{ $screening->room->room_name }}
            {{ \Carbon\Carbon::parse($screening->screening_date)->format('d/m/Y à H:i') }}
            {{ $screening->occupied_seats }}
            {{ $screening->room->capacity }}
            {{ $screening->occupancy_rate }} %<br>
        @endforeach
    @endif
@endsection
@extends('layouts.structure')

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

    <h2>Prochaines séances</h2>

    @if ($arScreenings->isEmpty())
        Aucune séance prévue.
    @else
        <table>
            <tr>
                <th>Date</th>
                <th>Salle</th>
                <th>Places disponibles</th>
                <th>Actions</th>
            </tr>
        @foreach($arScreenings as $screening)
            <tr>
                <td>{{ $screening->screening_date }}</td>
                <td>{{ $screening->room->room_name }}</td>
                <td>
                    @if ($screening->available_seats <= 0)
                        Séance complète
                    @else
                        Places disponibles : {{ $screening->available_seats }}
                    @endif
                </td>
                <td>
                    <a href="/make_reservation/{{ $screening->screening_id }}">Réserver</a>
                    @auth
                        @if ( Auth::user()->role_code == "A" )
                            <a href="/edit_screening/{{ $screening->screening_id }}">Modifier</a>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    @endif
@stop

@extends('layouts.structure')

@section('titre')
    {{ $objMovie->movie_name }}
@stop

@section('contenu')

    <h2>{{ $objMovie->movie_name }}</h2>

    <p><strong>Réalisateur :</strong> {{ $objMovie->director }}</p>
    <p><strong>Durée :</strong> {{ $objMovie->duration_minutes }} minutes</p>
    <p><strong>Description :</strong>
    @if ($objMovie->description == null)
        Non-renseigné
    @else
        {{ $objMovie->description }}
    @endif
    </p>

    <h2>Prochaines séances</h2>

    @if ($arScreenings->isEmpty())
        Aucune séance prévue.
    @else
        <table class="table table-striped table-bordered table-hover">
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
                    <a class="btn btn-primary" href="/make_reservation/{{ $screening->screening_id }}">Réserver</a>
                    @auth
                        @if ( Auth::user()->role_code == "A" )
                            <a class="btn btn-secondary" href="/edit_screening/{{ $screening->screening_id }}">Modifier</a>
                            <a class="btn btn-danger" href="/delete_screening/{{ $screening->screening_id }}">Supprimer</a>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    @endif
@stop

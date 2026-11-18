@extends('layouts.structure')

@section('titre')
    Tableau de bord
@stop

@section('contenu')
    <h2>Tableau de bord administrateur</h2>
    @if($arScreenings->isEmpty())
        <p>Aucune séance n'est actuellement enregistrée.</p>
    @else
        <table class="table table-striped table-bordered table-hover">
            <tr>
                <th>Nom</th>
                <th>Salle</th>
                <th>Date</th>
                <th>Places occupées</th>
                <th>Places totales</th>
                <th>Taux de remplissage</th>
            </tr>
            @foreach($arScreenings as $screening)
                <tr>
                    <td>{{ $screening->movie->movie_name }}</td>
                    <td>{{ $screening->room->room_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($screening->screening_date)->format('d/m/Y à H:i') }}</td>
                    <td>{{ $screening->occupied_seats }}</td>
                    <td>{{ $screening->room->capacity }}</td>
                    <td>{{ $screening->occupancy_rate }} %</td>
                </tr>
            @endforeach
        </table>
    @endif
@endsection

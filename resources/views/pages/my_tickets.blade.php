@extends('layouts.structure')

@section('titre')
    Mes billets
@endsection

@section('contenu')

    <h2>Mes billets</h2>

    @if($arReservations->isEmpty())

        <p>Vous n'avez pas encore effectué de réservation.</p>

    @else
        <table class="table table-striped table-bordered table-hover">
            <tr>
                <th>N° de réservation</th>
                <th>Film</th>
                <th>Date</th>
                <th>Salle</th>
                <th>Numéro de place</th>
                <th>Téléchargement</th>
            </tr>
        @foreach($arReservations as $reservation)
            <tr>
                <td>
                    {{ $reservation->reservation_id }}
                </td>
                <td>
                    {{ $reservation->screening->movie->movie_name }}
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($reservation->screening->screening_date)->format('d/m/Y à H:i') }}
                </td>
                <td>
                    {{ $reservation->screening->room->room_name }}
                </td>
                <td>
                    {{ $reservation->seat->seat_label }}
                </td>
                <td>
                    <a class="btn btn-primary" href="{{ url('/download_ticket/' . $reservation->reservation_id) }}">
                        Télécharger
                    </a>
                </td>
            </tr>
        @endforeach
        </table>
    @endif
@endsection

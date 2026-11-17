@extends('layouts.structure')

@section('titre')
    Mes billets
@endsection

@section('contenu')

    <h2>Mes réservations</h2>

    @if($arReservations->isEmpty())

        <p>Vous n'avez pas encore effectué de réservation.</p>

    @else
        <table>
            <tr>
                <th>N° de réservation</th>
                <th>Film</th>
                <th>Date</th>
                <th>Salle</th>
                <th>Nombre de places</th>
                <th>Prix total</th>
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
                    {{ $reservation->seats_quantity }}
                </td>
                <td>
                    {{ number_format($reservation->seats_quantity * 10, 2, ',', ' ') }} €
                </td>
                <td>
                    <a href="{{ url('/download_ticket/' . $reservation->reservation_id) }}">
                        Télécharger mon billet
                    </a>
                </td>
            </tr>
        @endforeach
        </table>
    @endif
@endsection
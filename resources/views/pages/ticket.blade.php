h2
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">

        <title>Billet de cinéma</title>
    </head>

    <body>

        <h1>Grand Cinéma</h1>

        <h2>Billet d'entrée pour :
            {{ $objReservation->screening->movie->movie_name }}
        </h2>

        Réalisateur : {{ $objReservation->screening->movie->director }}<br>
        Durée : {{ $objReservation->screening->movie->duration_minutes }} minutes<br>
        Date : {{ \Carbon\Carbon::parse($objReservation->screening->screening_date)->format('d/m/Y') }}<br>
        Horaire : {{ \Carbon\Carbon::parse($objReservation->screening->screening_date)->format('H:i') }}<br>
        Salle : {{ $objReservation->screening->room->room_name }}<br>

        <h3>Récapitulatif de la réservation</h3>

        N° de réservation : {{ $objReservation->reservation_id }}<br>
        Nom du client : {{ $objReservation->user->name }}<br>
        Nombre de places : {{ $objReservation->seats_quantity }}<br>
        Prix total :
                {{ number_format($objReservation->seats_quantity * 10, 2, ',', ' ') }} €<br><br>
        Présentez ce billet à l'entrée du cinéma.<br>
    </body>
</html>

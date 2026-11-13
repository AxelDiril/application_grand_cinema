<h1>{{ $objMovie->movie_name }}</h1>

Réalisateur : {{ $objMovie->director }}<br>
Durée : {{ $objMovie->duration_minutes }} minutes<br>
Description :
@if ($objMovie->description == null)
    Non-renseigné
@else
    {{ $objMovie->description }}
@endif
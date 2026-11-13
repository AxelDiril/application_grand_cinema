@if($arScreenings->isEmpty())
    Aucune séance à afficher.
@else
    @foreach($arScreenings as $screening)
        <a href="/movie/{{ $screening->movie_id }}">{{ $screening->movie->movie_name }}</a> le {{ $screening->screening_date }} ({{ $screening->movie->duration_minutes }} min)
        <br>
    @endforeach
@endif
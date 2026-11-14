@extends('layouts/structure')

@section('titre')
    {{ $objMovie->movie_name }}
@stop

@section('contenu')

  @if ($arScreenings->isEmpty())
      Aucune séance prévue.
  @else
      @foreach($arScreenings as $screening)
          Le {{ $screening->screening_date }} à {{ $screening->room->room_name }}
          @if ($screening->available_seats <= 0)
              Séance complète
          @else
              Places disponibles : {{ $screening->available_seats }}
          @endif
          <br>
      @endforeach
  @endif
@stop

@extends('layouts/structure')

@section('titre')
    Message
@stop

@section('contenu')
    <h2>Message</h2>

    {{ $strMessage }}<br>
    <a href="{{ $strLink }}">{{ $strLinkMessage }}</a>
@stop
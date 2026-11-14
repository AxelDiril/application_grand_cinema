@extends('layouts.structure')

@section('titre')
    Connexion
@stop

@section('contenu')
    <h1>Connexion</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email">Adresse e-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
            >
            @error('email')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                required
            >
            @error('password')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label>
                <input type="checkbox" name="remember">
                Se souvenir de moi
            </label>
        </div>

        <button type="submit">Se connecter</button>
    </form>

    <p>
        Pas encore de compte ?
        <a href="{{ route('register') }}">Créer un compte</a>
    </p>
@stop
```
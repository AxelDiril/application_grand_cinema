<h1>Le Grand Cinéma</h1>
<a href="/movies_list">Films</a>
<a href="/screenings_list">Séances</a>

@auth
Bienvenue, {{ Auth::user()->name }}.
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <input type="submit" value="Se déconnecter">
</form>
@else
<a href="{{ route('login') }}">Se connecter</a>
<a href="{{ route('register') }}">S'inscrire</a>
@endif
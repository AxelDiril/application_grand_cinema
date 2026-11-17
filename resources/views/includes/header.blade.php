<h1>Le Grand Cinéma</h1>
<a class="btn btn-light" href="/movies_list">Films</a>
<a class="btn btn-light" href="/screenings_list">Séances</a>

@auth
    <a class="btn btn-light" href="{{ url('/my_tickets') }}">Mes billets</a>
    @if ( Auth::user()->role_code == "A" || Auth::user()->role_code == "C")
        <a class="btn btn-light" href="/reservations_list">Réservations</a>
        <a class="btn btn-light" href="/admin_dashboard">Tableau de bord</a>
    @endif
    <form method="POST" action="{{ route('logout') }}">
    @csrf
    <input class="btn btn-light" type="submit" value="Se déconnecter">
</form>
@else
    <a class="btn btn-light" href="{{ route('login') }}">Se connecter</a>
    <a class="btn btn-light" href="{{ route('register') }}">S'inscrire</a>
@endif

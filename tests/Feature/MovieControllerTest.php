<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Movie;
use App\Models\Screening;
use App\Models\Room;
use App\Models\Genre;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MovieControllerTest extends TestCase
{
    use DatabaseTransactions;

    // Propriétés privées utilisées par les tests
    private $objMovie1;
    private $objMovie2;
    private $objRoom1;
    private $objGenre1;
    private $objGenre2;
    private $objScreening1;
    private $objScreening2;
    private $objScreeningPast;

    private $objUser;
    private $objRole;

    /**
     * Création des données communes avant chaque test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Création des films
        $this->objMovie1 = Movie::create([
            'movie_name' => 'Le Dîner Impossible',
            'director' => 'Maxime Roux',
            'duration_minutes' => 101,
            'description' => 'Un repas de famille tourne à la catastrophe.'
        ]);

        $this->objMovie2 = Movie::create([
            'movie_name' => 'Les Petits Explorateurs',
            'director' => 'Pauline Chevalier',
            'duration_minutes' => 79,
            'description' => 'Trois enfants partent à la recherche d’un trésor.'
        ]);

        // Création des genres
        $this->objGenre1 = Genre::create([
            'genre_label' => 'Action'
        ]);

        $this->objGenre2 = Genre::create([
            'genre_label' => 'Comédie'
        ]);

        // Association des genres aux films
        $this->objMovie1->genres()->attach($this->objGenre1->genre_id);
        $this->objMovie2->genres()->attach($this->objGenre2->genre_id);

        // Création de la salle
        $this->objRoom1 = Room::create([
            'room_name' => 'Salle Test',
            'capacity' => 100
        ]);

        // Séance future du premier film
        $this->objScreening1 = Screening::create([
            'movie_id' => $this->objMovie1->movie_id,
            'room_id' => $this->objRoom1->room_id,
            'screening_date' => Carbon::now()->addDay()
        ]);

        // Séance future du deuxième film
        $this->objScreening2 = Screening::create([
            'movie_id' => $this->objMovie2->movie_id,
            'room_id' => $this->objRoom1->room_id,
            'screening_date' => Carbon::now()->addDays(2)
        ]);

        // Séance passée du premier film
        $this->objScreeningPast = Screening::create([
            'movie_id' => $this->objMovie1->movie_id,
            'room_id' => $this->objRoom1->room_id,
            'screening_date' => Carbon::now()->subDay()
        ]);

        // Création d'un utilisateur et d'un rôle associé

        $this->objRole = Role::Create([
            'role_code' => 'U',
            'role_label' => 'Utilisateur'
        ]);

        $this->objUser = User::create([
            'name' => 'Utilisateur Test',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_code' => 'U'
        ]);
    }

    /**
     * Test 1 : Vérifie qu'un film apparaît dans la liste complète.
     */
    public function test_get_movie_list()
    {
        $response = $this->get('/movies_list');

        $response->assertStatus(200);
        $response->assertViewIs('pages.movies_list');

        $response->assertViewHas('arMovies', function ($movies) {
            return $movies->contains(
                'movie_id',
                $this->objMovie1->movie_id
            );
        });
    }

    /**
     * Test 2 : Vérifie qu'aucun film n'apparaît dans la liste.
     */
    public function test_get_movie_list_empty()
    {
        // Suppression des séances associées aux films de setUp()
        Screening::whereIn('movie_id', [
            $this->objMovie1->movie_id,
            $this->objMovie2->movie_id
        ])->delete();

        // Suppression des associations entre films et genres
        $this->objMovie1->genres()->detach();
        $this->objMovie2->genres()->detach();

        // Suppression des films
        Movie::whereIn('movie_id', [
            $this->objMovie1->movie_id,
            $this->objMovie2->movie_id
        ])->delete();

        $response = $this->get('/movies_list');

        $response->assertStatus(200);
        $response->assertViewIs('pages.movies_list');

        $response->assertViewHas('arMovies', function ($movies) {
            return $movies->isEmpty();
        });
    }

    /**
     * Test 3 : Vérifie qu'un film puisse être récupéré pour afficher ses détails.
     */
    public function test_get_movie()
    {
        $response = $this->get('/movie/' . $this->objMovie1->movie_id);

        $response->assertStatus(200);
        $response->assertViewIs('pages.movie');

        $response->assertViewHas('objMovie', function ($objMovie) {
            return $objMovie->movie_id === $this->objMovie1->movie_id;
        });
    }

    /**
     * Test 4 : Vérifie qu'un film inexistant affiche un message d'erreur.
     */
    public function test_get_movie_unknown()
    {
        $this->assertNull(Movie::find(999999));

        $response = $this->get('/movie/999999');

        $response->assertStatus(200);
        $response->assertViewIs('pages.message');
        $response->assertViewHas('strMessage', "Ce film n'existe pas.");
    }

    /**
     * Test 5 : Vérifie que seules les prochaines séances sont affichées.
     */
    public function test_get_movie_next_screenings()
    {
        $response = $this->get('/movie/' . $this->objMovie1->movie_id);

        $response->assertStatus(200);
        $response->assertViewIs('pages.movie');

        $response->assertViewHas('arScreenings', function ($screenings) {
            return $screenings->contains(
                'screening_id',
                $this->objScreening1->screening_id
            )
            && !$screenings->contains(
                'screening_id',
                $this->objScreeningPast->screening_id
            );
        });
    }

    /**
     * Test 6 : Vérifie que les séances correspondent au film choisi.
     */
    public function test_get_movie_screenings_of_selected_movie()
    {
        $response = $this->get('/movie/' . $this->objMovie1->movie_id);

        $response->assertStatus(200);

        $response->assertViewHas('arScreenings', function ($screenings) {
            return $screenings->contains(
                'screening_id',
                $this->objScreening1->screening_id
            )
            && !$screenings->contains(
                'screening_id',
                $this->objScreening2->screening_id
            );
        });
    }

    /**
     * Test 7 : Vérifie le filtre par nom de film.
     */
    public function test_filter_movies_by_name()
    {
        $response = $this->get('/movies_list?movie_name=Le Dîner');

        $response->assertStatus(200);

        $response->assertViewHas('arMovies', function ($movies) {
            return $movies->count() === 1
                && $movies->first()->movie_id === $this->objMovie1->movie_id;
        });
    }

    /**
     * Test 8 : Vérifie le filtre par genre.
     */
    public function test_filter_movies_by_genre()
    {
        $response = $this->get(
            '/movies_list?genre_id=' . $this->objGenre1->genre_id
        );

        $response->assertStatus(200);

        $response->assertViewHas('arMovies', function ($movies) {
            return $movies->count() === 1
                && $movies->first()->movie_id === $this->objMovie1->movie_id;
        });
    }

    /**
     * Test 9 : Vérifie la combinaison des filtres par nom et par genre.
     */
    public function test_filter_movies_by_name_and_genre()
    {
        $response = $this->get(
            '/movies_list?movie_name=Le Dîner'
            . '&genre_id=' . $this->objGenre1->genre_id
        );

        $response->assertStatus(200);

        $response->assertViewHas('arMovies', function ($movies) {
            return $movies->count() === 1
                && $movies->first()->movie_id === $this->objMovie1->movie_id;
        });
    }

    /**
     * Test 10 : Vérifier que toutes les places sont disponibles lorsqu'il n'y a aucune
     * réservation pour la séance.
     */
    public function test_screening_no_reservation()
    {
        $response = $this->get('/screenings_list');

        $response->assertOk();

        $arScreenings = $response->viewData('arScreenings');

        $screening = $arScreenings->firstWhere(
            'screening_id',
            $this->objScreening1->screening_id
        );

        $this->assertNotNull($screening);

        $this->assertEquals(
            $this->objRoom1->capacity,
            $screening->available_seats
        );
    }

    /**
     * Test 11 : Vérifie que le nombre de places disponibles est correct après plusieurs
     * réservations.
     */
    public function test_screening_several_reservations()
    {
        // Création de deux réservations pour la même séance
        DB::table('reservations')->insert([
            [
                'screening_id' => $this->objScreening1->screening_id,
                'id' => $this->objUser->id,
                'seats_quantity' => 3,
            ],
            [
                'screening_id' => $this->objScreening1->screening_id,
                'id' => $this->objUser->id,
                'seats_quantity' => 2,
            ],
        ]);

        $response = $this->get('/screenings_list');

        $response->assertOk();

        $arScreenings = $response->viewData('arScreenings');

        $screening = $arScreenings->firstWhere(
            'screening_id',
            $this->objScreening1->screening_id
        );

        $this->assertNotNull($screening);

        $this->assertEquals(
            $this->objRoom1->capacity - 5,
            $screening->available_seats
        );
    }
}

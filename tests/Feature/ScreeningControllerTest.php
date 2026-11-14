<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Genre;
use App\Models\Screening;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScreeningControllerTest extends TestCase
{

    use DatabaseTransactions;

    // Propriétés privées utilisées par les tests

    private $movie1;
    private $movie2;
    private $room1;
    private $room2;
    private $genre1;
    private $genre2;
    private $screening1;
    private $screening2;
    private $user;

    private $role;

    /**
     * Création des données communes avant chaque test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Création des films
        $this->movie1 = Movie::create([
            'movie_name' => 'Film Test A',
            'director' => 'Réalisateur A',
            'duration_minutes' => 120,
            'description' => 'Description A'
        ]);

        $this->movie2 = Movie::create([
            'movie_name' => 'Film Test B',
            'director' => 'Réalisateur B',
            'duration_minutes' => 90,
            'description' => 'Description B'
        ]);

        // Création des genres
        $this->genre1 = Genre::create([
            'genre_label' => 'Action'
        ]);

        $this->genre2 = Genre::create([
            'genre_label' => 'Comédie'
        ]);

        // Association des genres aux films
        $this->movie1->genres()->attach($this->genre1->genre_id);
        $this->movie2->genres()->attach($this->genre2->genre_id);

        // Création des salles
        $this->room1 = Room::create([
            'room_name' => 'Salle Test A',
            'capacity' => 100
        ]);

        $this->room2 = Room::create([
            'room_name' => 'Salle Test B',
            'capacity' => 150
        ]);

        // Création des séances
        $this->screening1 = Screening::create([
            'movie_id' => $this->movie1->movie_id,
            'room_id' => $this->room1->room_id,
            'screening_date' => '2026-12-10 14:00:00'
        ]);

        $this->screening2 = Screening::create([
            'movie_id' => $this->movie2->movie_id,
            'room_id' => $this->room2->room_id,
            'screening_date' => '2026-12-11 18:00:00'
        ]);

        // Création d'un utilisateur et d'un rôle associé

        $this->role = Role::Create([
            'role_code' => 'U',
            'role_label' => 'Utilisateur'  
        ]);

        $this->user = User::create([
            'name' => 'Utilisateur Test',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_code' => 'U'
        ]);
    }

    /**
     * Test 1 : Vérifie qu'une séance présente dans la table screenings
     * apparaît dans la liste.
     */
    public function test_get_screenings_list()
    {
        $response = $this->get('/screenings_list');

        $response->assertStatus(200);
        $response->assertViewIs('pages.screenings_list');

        $response->assertViewHas('arScreenings', function ($screenings) {
            return $screenings->contains(
                'screening_id',
                $this->screening1->screening_id
            );
        });
    }

    /**
     * Test 2 : Vérifie que les films et la salle de la séance sont correctement chargés.
     */
    public function test_get_screenings_list_loads_movie_and_room()
    {
        $response = $this->get('/screenings_list');

        $response->assertStatus(200);

        $response->assertViewHas('arScreenings', function ($screenings) {
            $screening = $screenings->firstWhere(
                'screening_id',
                $this->screening1->screening_id
            );

            return $screening !== null
                && $screening->relationLoaded('movie')
                && $screening->relationLoaded('room');
        });
    }

    /** 
    * Test 3 : Vérifier le du filtre par nom du film.
    */
    public function test_filter_screenings_by_movie_name()
    {
        $response = $this->get('/screenings_list?movie_name=Film Test A');

        $response->assertStatus(200);
        $response->assertViewHas('arScreenings', function ($screenings) {
            return $screenings->count() === 1
                && $screenings->first()->screening_id === $this->screening1->screening_id;
        });
    }

    /*
    * Test 4 : Vérifier le filtre par genre.
    */
    public function test_filter_screenings_by_genre()
    {
        $response = $this->get('/screenings_list?genre_id=' . $this->genre1->genre_id);

        $response->assertStatus(200);
        $response->assertViewHas('arScreenings', function ($screenings) {
            return $screenings->count() === 1
                && $screenings->first()->screening_id === $this->screening1->screening_id;
        });
    }

    /*
    * Test 5 : Vérifier le filtre par salle.
    */
    public function test_filter_screenings_by_room()
    {
        $response = $this->get('/screenings_list?room_id=' . $this->room1->room_id);

        $response->assertStatus(200);
        $response->assertViewHas('arScreenings', function ($screenings) {
            return $screenings->count() === 1
                && $screenings->first()->screening_id === $this->screening1->screening_id;
        });
    }

    /*
    * Test 6 : Vérifier le filtre par date.
    */

    public function test_filter_screenings_by_date()
    {
        $response = $this->get('/screenings_list?screening_date=2026-12-10');

        $response->assertStatus(200);
        $response->assertViewHas('arScreenings', function ($screenings) {
            return $screenings->count() === 1
                && $screenings->first()->screening_id === $this->screening1->screening_id;
        });
    }

    /*
    * Test 7 : Vérifier plusieurs filtres simultanés.
    */
    
    public function test_filter_screenings_with_multiple_filters()
    {
        $response = $this->get(
            '/screenings_list?movie_name=Film Test A'
            . '&genre_id=' . $this->genre1->genre_id
            . '&room_id=' . $this->room1->room_id
            . '&screening_date=2026-12-10'
        );

        $response->assertStatus(200);
        $response->assertViewHas('arScreenings', function ($screenings) {
            return $screenings->count() === 1
                && $screenings->first()->screening_id === $this->screening1->screening_id;
        });
    }

    /*
    * Test 8 : Vérifier l'envoi des valeurs des filtres dans la vue.
    */ 
    public function test_filter_values_are_returned_to_view()
    {
        $response = $this->get('/screenings_list?movie_name=Film Test A'
            . '&genre_id=' . $this->genre1->genre_id
            . '&room_id=' . $this->room1->room_id
            . '&screening_date=2026-12-10'
        );

        $response->assertStatus(200);
        $response->assertViewHas('strName', 'Film Test A');
        $response->assertViewHas('iGenreId', (string) $this->genre1->genre_id);
        $response->assertViewHas('iRoomId', (string) $this->room1->room_id);
        $response->assertViewHas('strDate', '2026-12-10');
    }

    /**
     * Test 9 : Vérifier que toutes les places sont disponibles lorsqu'il n'y a aucune
     * réservation pour la séance.
     */
    public function test_screening_no_reservation()
    {
        $response = $this->get('/screenings_list');

        $response->assertOk();

        $arScreenings = $response->viewData('arScreenings');

        $screening = $arScreenings->firstWhere(
            'screening_id',
            $this->screening1->screening_id
        );

        $this->assertNotNull($screening);

        $this->assertEquals(
            $this->room1->capacity,
            $screening->available_seats
        );
    }

    /**
     * Test 10 : Vérifie que le nombre de places disponibles est correct après plusieurs
     * réservations.
     */
    public function test_screening_several_reservations()
    {
        // Création de deux réservations pour la même séance
        DB::table('reservations')->insert([
            [
                'screening_id' => $this->screening1->screening_id,
                'id' => $this->user->id,
                'seats_quantity' => 3,
            ],
            [
                'screening_id' => $this->screening1->screening_id,
                'id' => $this->user->id,
                'seats_quantity' => 2,
            ],
        ]);

        $response = $this->get('/screenings_list');

        $response->assertOk();

        $arScreenings = $response->viewData('arScreenings');

        $screening = $arScreenings->firstWhere(
            'screening_id',
            $this->screening1->screening_id
        );

        $this->assertNotNull($screening);

        $this->assertEquals(
            $this->room1->capacity - 5,
            $screening->available_seats
        );
    }
}

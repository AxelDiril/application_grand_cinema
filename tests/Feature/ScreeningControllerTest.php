<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Genre;
use App\Models\Screening;
use App\Models\User;
use App\Models\Role;
use App\Models\Reservation;
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
    private $user1;
    private $user2;
    private $roleU;
    private $roleA;

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

        $this->roleU = Role::Create([
            'role_code' => 'U',
            'role_label' => 'Utilisateur'  
        ]);

        $this->user1 = User::create([
            'name' => 'Utilisateur Test',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_code' => 'U'
        ]);

        // Création d'un administrateur et d'un rôle associé

        $this->roleA = Role::Create([
            'role_code' => 'A',
            'role_label' => 'Administrateur'  
        ]);

        $this->user2 = User::create([
            'name' => 'Utilisateur Test',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
            'role_code' => 'A'
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
                'id' => $this->user1->id,
                'seats_quantity' => 3,
            ],
            [
                'screening_id' => $this->screening1->screening_id,
                'id' => $this->user1->id,
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

    /**
     * Test 11 : Vérifie qu'un utilisateur non-adminastrateur ne puisse pas accéder à l'ajout
     * de séances.
     */
    public function test_add_screening_refuses_non_admin()
    {
        $response = $this->actingAs($this->user1)
            ->get('/add_screening');

        $response->assertRedirect('/');
    }

    /**
     * Test 12 : Vérifie qu'un administrateur peut accéder à l'ajout de séances.
     */
    public function test_add_screening_admin()
    {

        $response = $this->actingAs($this->user2)
            ->get('/add_screening');

        $response->assertOk();
        $response->assertViewIs('pages.add_screening');

        $response->assertViewHas('arRooms');
        $response->assertViewHas('arMovies');

        $response->assertViewHas('arRooms', function ($rooms) {
            return $rooms->contains('room_id', $this->room1->room_id);
        });

        $response->assertViewHas('arMovies', function ($movies) {
            return $movies->contains('movie_id', $this->movie1->movie_id);
        });
    }

    /**
     * Test 13 : Vérifie qu'un utilisateur non-administrateur ne puisse insérer une séance dans
     * la base de données.
     */
    public function test_insert_screening_refuses_non_admin()
    {
        $response = $this->actingAs($this->user1)
            ->post('/insert_screening', [
                'movie_id' => $this->movie1->movie_id,
                'room_id' => $this->room1->room_id,
                'screening_date' => '2026-12-12T15:30',
            ]);

        $response->assertRedirect('/');
    }

    /**
     * Test 14 : Vérifie qu'un administrateur puisse insérer une séance dans la base de données.
     */
    public function test_insert_screening_admin()
    {

        $response = $this->actingAs($this->user2)
            ->post('/insert_screening', [
                'movie_id' => $this->movie1->movie_id,
                'room_id' => $this->room1->room_id,
                'screening_date' => '2026-12-12T15:30',
            ]);

        $response->assertOk();
        $response->assertViewIs('pages.message');
        $response->assertViewHas(
            'strMessage',
            'La séance a bien été créée.'
        );

        $this->assertDatabaseHas('screenings', [
            'movie_id' => $this->movie1->movie_id,
            'room_id' => $this->room1->room_id,
            'screening_date' => '2026-12-12 15:30:00',
        ]);
    }

    /**
     * Test 15 : Vérifie qu'une même séance ne peut pas être créée deux fois.
     */
    public function test_insert_screening_refuses_duplicate_screening()
    {
        $response = $this->actingAs($this->user2)
            ->post('/insert_screening', [
                'movie_id' => $this->movie2->movie_id,
                'room_id' => $this->room1->room_id,
                'screening_date' => '2026-12-10T14:00',
            ]);

        $response->assertOk();
        $response->assertViewIs('pages.message');
        $response->assertViewHas(
            'strMessage',
            'Une séance existe déjà pour cette salle et cet horaire.'
        );

        // Aucune séance supplémentaire ne doit être créée
        $this->assertDatabaseCount('screenings', 2);
    }

    /**
     * Test 16 : Vérifie qu'un utilisateur non administrateur ne puisse pas modifier une séance.
     */
    public function test_update_screening_refuses_non_admin()
    {
        $response = $this->actingAs($this->user1)
            ->post('/update_screening/' . $this->screening1->screening_id, [
                'movie_id' => $this->movie2->movie_id,
                'room_id' => $this->room2->room_id,
                'screening_date' => '2026-12-12T15:30',
            ]);

        $response->assertRedirect('/');
    }

    /**
     * Test 17 : Vérifie qu'un administrateur puisse modifier une séance.
     */
    public function test_update_screening_modifies_screening()
    {
        $response = $this->actingAs($this->user2)
            ->post('/update_screening/' . $this->screening1->screening_id, [
                'movie_id' => $this->movie2->movie_id,
                'room_id' => $this->room2->room_id,
                'screening_date' => '2026-12-12T15:30',
            ]);

        $response->assertOk();
        $response->assertViewIs('pages.message');
        $response->assertViewHas(
            'strMessage',
            'La séance a bien été modifiée.'
        );

        $this->assertDatabaseHas('screenings', [
            'screening_id' => $this->screening1->screening_id,
            'movie_id' => $this->movie2->movie_id,
            'room_id' => $this->room2->room_id,
            'screening_date' => '2026-12-12 15:30:00',
        ]);
    }

    /**
     * Test 18 : Vérifie qu'un administrateur ne puisse pas enregistrer une séance si une autre
     * existe à la même salle et même horaire.
     */
    public function test_update_screening_refuses_duplicate_screening()
    {
        $response = $this->actingAs($this->user2)
            ->post('/update_screening/' . $this->screening1->screening_id, [
                'movie_id' => $this->movie2->movie_id,
                'room_id' => $this->room2->room_id,
                'screening_date' => '2026-12-11T18:00',
            ]);

        $response->assertOk();
        $response->assertViewIs('pages.message');
        $response->assertViewHas(
            'strMessage',
            'Une séance existe déjà pour cette salle et cet horaire.'
        );

        // Vérifier que la séance initiale n'a pas été modifiée
        $this->assertDatabaseHas('screenings', [
            'screening_id' => $this->screening1->screening_id,
            'movie_id' => $this->movie1->movie_id,
            'room_id' => $this->room1->room_id,
            'screening_date' => '2026-12-10 14:00:00',
        ]);
    }

    /**
     * Test 19 : Vérifie qu'un utilisateur authentifié puisse accéder à la page de réservation.
     */
    public function test_make_reservation()
    {
        $response = $this->actingAs($this->user1)
            ->get('/make_reservation/' . $this->screening1->screening_id);

        $response->assertOk();

        $response->assertViewIs('pages.make_reservation');

        $response->assertViewHas('objScreening', function ($screening) {
            return $screening->screening_id == $this->screening1->screening_id;
        });

        $response->assertViewHas('iAvailableSeats', $this->room1->capacity);
    }

    /**
     * Test 20 : Vérifie qu'un utilisateur non authentifié ne peut pas accéder à la réservation.
     */
    public function test_make_reservation_unauthenticated()
    {
        $response = $this->get('/make_reservation/' . $this->screening1->screening_id);

        $response->assertOk();

        $response->assertViewIs('pages.message');

        $response->assertViewHas('strMessage', 
            "Vous devez vous connecter pour effectuer une réservation."
        );

        $response->assertViewHas('strLink', '/login');

        $response->assertViewHas('strLinkMessage', 'Connexion');
    }

    /**
     * Test 21 : Vérifie qu'un utilisateur authentifié puisse afficher le récapitulatif.
     */
    public function test_confirm_reservation()
    {
        $response = $this->actingAs($this->user1)
            ->post('/confirm_reservation/' . $this->screening1->screening_id, [
                'seats_quantity' => 3
            ]);

        $response->assertOk();

        $response->assertViewIs('pages.confirm_reservation');

        $response->assertViewHas('objScreening');

        $response->assertViewHas('iSeatsQuantity', 3);

        $response->assertViewHas('fTotalPrice', 30);
    }

    /**
     * Test 22 : Vérifier qu'un utilisateur non-authentifié ne puisse pas afficher
     * le récapitulatif.
     */
    public function test_confirm_reservation_nauthenticated()
    {
        $response = $this->post('/confirm_reservation/' . $this->screening1->screening_id, [
            'seats_quantity' => 3
        ]);

        $response->assertRedirect('/');
    }

    /**
     * Test 23 : Vérifie que le nombre de places doit être supérieur ou égal à 1.
     */
    public function test_confirm_reservation_invalid_seats_quantity()
    {
        $response = $this->actingAs($this->user1)
            ->post('/confirm_reservation/' . $this->screening1->screening_id, [
                'seats_quantity' => 0
            ]);

        $response->assertSessionHasErrors('seats_quantity');
    }

    /**
     * Test 24 : Vérifie que le nombre de places demandé ne puisse pas dépasser les places disponibles.
     */
    public function test_confirm_reservation_refuses_too_many_seats()
    {
        $response = $this->actingAs($this->user1)
            ->post('/confirm_reservation/' . $this->screening1->screening_id, [
                'seats_quantity' => $this->room1->capacity + 1
            ]);

        $response->assertRedirect('/make_reservation/' . $this->screening1->screening_id);
    }

    /**
     * Test 25 : Vérifie l'affichage du tableau de bord administrateur.
     */
    public function test_get_admin_dashboard(): void
    {
        $response = $this->actingAs($this->user2)
            ->get('/admin_dashboard');

        $response->assertOk();
        $response->assertViewIs('pages.admin_dashboard');
        $response->assertViewHas('arScreenings');

        $arScreenings = $response->viewData('arScreenings');

        // Vérifie que les séances sont bien présentes
        $this->assertCount(2, $arScreenings);

        // Vérifie que les séances sont classées de la plus récente à la plus ancienne
        $this->assertTrue(
            $arScreenings[0]->screening_date >= $arScreenings[1]->screening_date
        );
    }

    /**
     * Test 26 : Vérifie le calcul du nombre de places occupées et du pourcentage de remplissage.
     */
    public function test_get_admin_dashboard_occupancy_calculation(): void
    {
        // Création des réservations pour tester le taux de remplissage
        Reservation::create([
            'screening_id' => $this->screening1->screening_id,
            'id' => $this->user1->id,
            'seats_quantity' => 3
        ]);

        Reservation::create([
            'screening_id' => $this->screening2->screening_id,
            'id' => $this->user2->id,
            'seats_quantity' => 2
        ]);
        $response = $this->actingAs($this->user2)
            ->get('/admin_dashboard');

        $response->assertOk();

        $arScreenings = $response->viewData('arScreenings');

        // Récupère les séances correspondantes
        $objScreening1 = $arScreenings->firstWhere(
            'screening_id',
            $this->screening1->screening_id
        );

        $objScreening2 = $arScreenings->firstWhere(
            'screening_id',
            $this->screening2->screening_id
        );

        // Vérifie le nombre de places occupées
        $this->assertEquals(3, $objScreening1->occupied_seats);
        $this->assertEquals(2, $objScreening2->occupied_seats);

        // Vérifie les pourcentages de remplissage
        $this->assertEquals(
            round((3 / $this->room1->capacity) * 100, 2),
            $objScreening1->occupancy_rate
        );

        $this->assertEquals(
            round((2 / $this->room2->capacity) * 100, 2),
            $objScreening2->occupancy_rate
        );
    }

    /**
     * Test 27 : Vérifie qu'un utilisateur non-connecté ne puisse pas accéder au tableau de bord.
     */
    public function test_get_admin_dashboard_unauthenticated(): void
    {
        $response = $this->get('/admin_dashboard');

        $response->assertRedirect('/');
    }

    /**
     * Test 28 : Vérifie qu'un utilisateur non-administrateur ne puisse pas accéder au tableau de bord.
     */
    public function test_get_admin_dashboard_unauthorized(): void
    {
        $response = $this->actingAs($this->user1)
            ->get('/admin_dashboard');

        $response->assertRedirect('/');
    }

    /**
     * Test 29 : Vérifie la suppression d'une séance par un administrateur.
     */
    public function test_delete_screening()
    {
        $this->actingAs($this->user2);

        $response = $this->get('/delete_screening/' . $this->screening1->screening_id);

        $response->assertOk();
        $response->assertViewIs('pages.message');

        $response->assertViewHas('strMessage', "La séance a bien été supprimée.");
        $response->assertViewHas('strLink', '/screenings_list');

        $this->assertDatabaseMissing('screenings', [
            'screening_id' => $this->screening1->screening_id
        ]);
    }

    /**
     * Test 30 : Vérifie qu'un visiteur non connecté ne peut pas supprimer une séance.
     */
    public function test_delete_screening_unauthenticated()
    {
        $response = $this->get('/delete_screening/' . $this->screening1->screening_id);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('screenings', [
            'screening_id' => $this->screening1->screening_id
        ]);
    }

    /**
     * Test 31 : Vérifie qu'on ne puisse pas supprimer une séance inexistante.
     */
    public function test_delete_unknown_screening()
    {
        $this->actingAs($this->user2);

        $response = $this->get('/delete_screening/999999');

        $response->assertRedirect('/');
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Seat;
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
    private $objMovie1;
    private $objMovie2;
    private $objRoom1;
    private $objRoom2;
    private $objGenre1;
    private $objGenre2;
    private $objScreening1;
    private $objScreening2;
    private $objUser1;
    private $objUser2;
    private $objRoleU;
    private $objRoleA;
    private $arSeatsRoom1;
    private $arSeatsRoom2;

    /**
     * Création des données communes avant chaque test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Création des films
        $this->objMovie1 = Movie::create([
            'movie_name' => 'Film Test A',
            'director' => 'Réalisateur A',
            'duration_minutes' => 120,
            'description' => 'Description A'
        ]);

        $this->objMovie2 = Movie::create([
            'movie_name' => 'Film Test B',
            'director' => 'Réalisateur B',
            'duration_minutes' => 90,
            'description' => 'Description B'
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

        // Création des salles, sans colonne capacity
        $this->objRoom1 = Room::create([
            'room_name' => 'Salle Test A'
        ]);

        $this->objRoom2 = Room::create([
            'room_name' => 'Salle Test B'
        ]);

        // Création de 100 places dans la première salle
        $this->arSeatsRoom1 = collect();

        for ($i = 1; $i <= 100; $i++) {
            $this->arSeatsRoom1->push(Seat::create([
                'room_id' => $this->objRoom1->room_id,
                'seat_label' => (string) $i
            ]));
        }

        // Création de 150 places dans la deuxième salle
        $this->arSeatsRoom2 = collect();

        for ($i = 1; $i <= 150; $i++) {
            $this->arSeatsRoom2->push(Seat::create([
                'room_id' => $this->objRoom2->room_id,
                'seat_label' => (string) $i
            ]));
        }

        // Création des séances
        $this->objScreening1 = Screening::create([
            'movie_id' => $this->objMovie1->movie_id,
            'room_id' => $this->objRoom1->room_id,
            'screening_date' => '2026-12-10 14:00:00'
        ]);

        $this->objScreening2 = Screening::create([
            'movie_id' => $this->objMovie2->movie_id,
            'room_id' => $this->objRoom2->room_id,
            'screening_date' => '2026-12-11 18:00:00'
        ]);

        // Création d'un utilisateur et de son rôle
        $this->objRoleU = Role::create([
            'role_code' => 'U',
            'role_label' => 'Utilisateur'
        ]);

        $this->objUser1 = User::create([
            'name' => 'Utilisateur Test',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_code' => 'U'
        ]);

        // Création d'un administrateur et de son rôle
        $this->objRoleA = Role::create([
            'role_code' => 'A',
            'role_label' => 'Administrateur'
        ]);

        $this->objUser2 = User::create([
            'name' => 'Administrateur Test',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
            'role_code' => 'A'
        ]);
    }

    /**
     * Test 1 : Vérifie qu'une séance apparaît dans la liste.
     */
    public function test_get_screenings_list()
    {
        $response = $this->get('/screenings_list');

        $response->assertStatus(200);
        $response->assertViewIs('pages.screenings_list');

        $response->assertViewHas('arScreenings', function ($screenings) {
            return $screenings->contains(
                'screening_id',
                $this->objScreening1->screening_id
            );
        });
    }

    /**
     * Test 2 : Vérifie que le film et la salle de la séance sont chargés.
     */
    public function test_get_screenings_list_loads_movie_and_room()
    {
        $response = $this->get('/screenings_list');

        $response->assertStatus(200);

        $response->assertViewHas('arScreenings', function ($screenings) {
            $screening = $screenings->firstWhere(
                'screening_id',
                $this->objScreening1->screening_id
            );

            return $screening !== null
                && $screening->relationLoaded('movie')
                && $screening->relationLoaded('room');
        });
    }

    /**
     * Test 3 : Vérifie le filtre par nom du film.
     */
    public function test_filter_screenings_by_movie_name()
    {
        $response = $this->get('/screenings_list?movie_name=Film Test A');

        $response->assertStatus(200);
        $response->assertViewHas('arScreenings', function ($screenings) {
            return $screenings->count() === 1
                && $screenings->first()->screening_id === $this->objScreening1->screening_id;
        });
    }

    /**
     * Test 4 : Vérifie le filtre par genre.
     */
    public function test_filter_screenings_by_genre()
    {
        $response = $this->get('/screenings_list?genre_id=' . $this->objGenre1->genre_id);

        $response->assertStatus(200);
        $response->assertViewHas('arScreenings', function ($screenings) {
            return $screenings->count() === 1
                && $screenings->first()->screening_id === $this->objScreening1->screening_id;
        });
    }

    /**
     * Test 5 : Vérifie le filtre par salle.
     */
    public function test_filter_screenings_by_room()
    {
        $response = $this->get('/screenings_list?room_id=' . $this->objRoom1->room_id);

        $response->assertStatus(200);
        $response->assertViewHas('arScreenings', function ($screenings) {
            return $screenings->count() === 1
                && $screenings->first()->screening_id === $this->objScreening1->screening_id;
        });
    }

    /**
     * Test 6 : Vérifie le filtre par date.
     */
    public function test_filter_screenings_by_date()
    {
        $response = $this->get('/screenings_list?screening_date=2026-12-10');

        $response->assertStatus(200);
        $response->assertViewHas('arScreenings', function ($screenings) {
            return $screenings->count() === 1
                && $screenings->first()->screening_id === $this->objScreening1->screening_id;
        });
    }

    /**
     * Test 7 : Vérifie plusieurs filtres simultanés.
     */
    public function test_filter_screenings_with_multiple_filters()
    {
        $response = $this->get(
            '/screenings_list?movie_name=Film Test A'
            . '&genre_id=' . $this->objGenre1->genre_id
            . '&room_id=' . $this->objRoom1->room_id
            . '&screening_date=2026-12-10'
        );

        $response->assertStatus(200);
        $response->assertViewHas('arScreenings', function ($screenings) {
            return $screenings->count() === 1
                && $screenings->first()->screening_id === $this->objScreening1->screening_id;
        });
    }

    /**
     * Test 8 : Vérifie l'envoi des valeurs des filtres dans la vue.
     */
    public function test_filter_values_are_returned_to_view()
    {
        $response = $this->get(
            '/screenings_list?movie_name=Film Test A'
            . '&genre_id=' . $this->objGenre1->genre_id
            . '&room_id=' . $this->objRoom1->room_id
            . '&screening_date=2026-12-10'
        );

        $response->assertStatus(200);
        $response->assertViewHas('strName', 'Film Test A');
        $response->assertViewHas('iGenreId', (string) $this->objGenre1->genre_id);
        $response->assertViewHas('iRoomId', (string) $this->objRoom1->room_id);
        $response->assertViewHas('strDate', '2026-12-10');
    }

    /**
     * Test 9 : Vérifie que toutes les places sont disponibles sans réservation.
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
            $this->arSeatsRoom1->count(),
            $screening->available_seats
        );
    }

    /**
     * Test 10 : Vérifie le nombre de places disponibles après plusieurs réservations.
     */
    public function test_screening_several_reservations()
    {
        // Deux réservations correspondent à deux places distinctes
        DB::table('reservations')->insert([
            [
                'screening_id' => $this->objScreening1->screening_id,
                'id' => $this->objUser1->id,
                'seat_id' => $this->arSeatsRoom1[0]->seat_id,
            ],
            [
                'screening_id' => $this->objScreening1->screening_id,
                'id' => $this->objUser1->id,
                'seat_id' => $this->arSeatsRoom1[1]->seat_id,
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
            $this->arSeatsRoom1->count() - 2,
            $screening->available_seats
        );
    }

    /**
     * Test 11 : Vérifie qu'un utilisateur non-administrateur ne puisse pas accéder à l'ajout
     * de séances.
     */
    public function test_add_screening_refuses_non_admin()
    {
        $response = $this->actingAs($this->objUser1)
            ->get('/add_screening');

        $response->assertRedirect('/');
    }

    /**
     * Test 12 : Vérifie qu'un administrateur peut accéder à l'ajout de séances.
     */
    public function test_add_screening_admin()
    {
        $response = $this->actingAs($this->objUser2)
            ->get('/add_screening');

        $response->assertOk();
        $response->assertViewIs('pages.add_screening');

        $response->assertViewHas('arRooms');
        $response->assertViewHas('arMovies');

        $response->assertViewHas('arRooms', function ($rooms) {
            return $rooms->contains('room_id', $this->objRoom1->room_id);
        });

        $response->assertViewHas('arMovies', function ($movies) {
            return $movies->contains('movie_id', $this->objMovie1->movie_id);
        });
    }

    /**
     * Test 13 : Vérifie qu'un utilisateur non-administrateur ne puisse pas insérer une séance.
     */
    public function test_insert_screening_refuses_non_admin()
    {
        $response = $this->actingAs($this->objUser1)
            ->post('/insert_screening', [
                'movie_id' => $this->objMovie1->movie_id,
                'room_id' => $this->objRoom1->room_id,
                'screening_date' => '2026-12-12T15:30',
            ]);

        $response->assertRedirect('/');
    }

    /**
     * Test 14 : Vérifie qu'un administrateur puisse insérer une séance.
     */
    public function test_insert_screening_admin()
    {
        $response = $this->actingAs($this->objUser2)
            ->post('/insert_screening', [
                'movie_id' => $this->objMovie1->movie_id,
                'room_id' => $this->objRoom1->room_id,
                'screening_date' => '2026-12-12T15:30',
            ]);

        $response->assertOk();
        $response->assertViewIs('pages.message');
        $response->assertViewHas(
            'strMessage',
            'La séance a bien été créée.'
        );

        $this->assertDatabaseHas('screenings', [
            'movie_id' => $this->objMovie1->movie_id,
            'room_id' => $this->objRoom1->room_id,
            'screening_date' => '2026-12-12 15:30:00',
        ]);
    }

    /**
     * Test 15 : Vérifie qu'une même séance ne peut pas être créée deux fois.
     */
    public function test_insert_screening_refuses_duplicate_screening()
    {
        $response = $this->actingAs($this->objUser2)
            ->post('/insert_screening', [
                'movie_id' => $this->objMovie2->movie_id,
                'room_id' => $this->objRoom1->room_id,
                'screening_date' => '2026-12-10T14:00',
            ]);

        $response->assertOk();
        $response->assertViewIs('pages.message');
        $response->assertViewHas(
            'strMessage',
            'Une séance existe déjà pour cette salle et cet horaire.'
        );

        $this->assertDatabaseCount('screenings', 2);
    }

    /**
     * Test 16 : Vérifie qu'un utilisateur non administrateur ne puisse pas modifier une séance.
     */
    public function test_update_screening_refuses_non_admin()
    {
        $response = $this->actingAs($this->objUser1)
            ->post('/update_screening/' . $this->objScreening1->screening_id, [
                'movie_id' => $this->objMovie2->movie_id,
                'room_id' => $this->objRoom2->room_id,
                'screening_date' => '2026-12-12T15:30',
            ]);

        $response->assertRedirect('/');
    }

    /**
     * Test 17 : Vérifie qu'un administrateur puisse modifier une séance.
     */
    public function test_update_screening_modifies_screening()
    {
        $response = $this->actingAs($this->objUser2)
            ->post('/update_screening/' . $this->objScreening1->screening_id, [
                'movie_id' => $this->objMovie2->movie_id,
                'room_id' => $this->objRoom2->room_id,
                'screening_date' => '2026-12-12T15:30',
            ]);

        $response->assertOk();
        $response->assertViewIs('pages.message');
        $response->assertViewHas(
            'strMessage',
            'La séance a bien été modifiée.'
        );

        $this->assertDatabaseHas('screenings', [
            'screening_id' => $this->objScreening1->screening_id,
            'movie_id' => $this->objMovie2->movie_id,
            'room_id' => $this->objRoom2->room_id,
            'screening_date' => '2026-12-12 15:30:00',
        ]);
    }

    /**
     * Test 18 : Vérifie qu'un administrateur ne puisse pas modifier une séance
     * vers une salle et un horaire déjà occupés.
     */
    public function test_update_screening_refuses_duplicate_screening()
    {
        $response = $this->actingAs($this->objUser2)
            ->post('/update_screening/' . $this->objScreening1->screening_id, [
                'movie_id' => $this->objMovie2->movie_id,
                'room_id' => $this->objRoom2->room_id,
                'screening_date' => '2026-12-11T18:00',
            ]);

        $response->assertOk();
        $response->assertViewIs('pages.message');
        $response->assertViewHas(
            'strMessage',
            'Une séance existe déjà pour cette salle et cet horaire.'
        );

        $this->assertDatabaseHas('screenings', [
            'screening_id' => $this->objScreening1->screening_id,
            'movie_id' => $this->objMovie1->movie_id,
            'room_id' => $this->objRoom1->room_id,
            'screening_date' => '2026-12-10 14:00:00',
        ]);
    }

    /**
     * Test 19 : Vérifie qu'un utilisateur authentifié puisse accéder à la page de réservation.
     */
    public function test_make_reservation()
    {
        $response = $this->actingAs($this->objUser1)
            ->get('/make_reservation/' . $this->objScreening1->screening_id);

        $response->assertOk();
        $response->assertViewIs('pages.make_reservation');

        $response->assertViewHas('objScreening', function ($screening) {
            return $screening->screening_id == $this->objScreening1->screening_id;
        });

        $response->assertViewHas('iAvailableSeats', $this->arSeatsRoom1->count());

        $response->assertViewHas('arAvailableSeats', function ($seats) {
            return $seats->count() === $this->arSeatsRoom1->count()
                && $seats->contains('seat_id', $this->arSeatsRoom1[0]->seat_id);
        });
    }

    /**
     * Test 20 : Vérifie qu'un utilisateur non authentifié ne peut pas accéder à la réservation.
     */
    public function test_make_reservation_unauthenticated()
    {
        $response = $this->get('/make_reservation/' . $this->objScreening1->screening_id);

        $response->assertOk();
        $response->assertViewIs('pages.message');

        $response->assertViewHas(
            'strMessage',
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
        $objSeat = $this->arSeatsRoom1[0];

        $response = $this->actingAs($this->objUser1)
            ->post('/confirm_reservation/' . $this->objScreening1->screening_id, [
                'seat_id' => $objSeat->seat_id
            ]);

        $response->assertOk();
        $response->assertViewIs('pages.confirm_reservation');
        $response->assertViewHas('objScreening');
        $response->assertViewHas('objSeat', function ($seat) use ($objSeat) {
            return $seat->seat_id === $objSeat->seat_id;
        });
        $response->assertViewHas('fTotalPrice', 10);
    }

    /**
     * Test 22 : Vérifie qu'un utilisateur non authentifié ne puisse pas afficher le récapitulatif.
     */
    public function test_confirm_reservation_unauthenticated()
    {
        $response = $this->post('/confirm_reservation/' . $this->objScreening1->screening_id, [
            'seat_id' => $this->arSeatsRoom1[0]->seat_id
        ]);

        $response->assertRedirect('/');
    }

    /**
     * Test 23 : Vérifie qu'un identifiant de place invalide est refusé.
     */
    public function test_confirm_reservation_invalid_seat()
    {
        $response = $this->actingAs($this->objUser1)
            ->post('/confirm_reservation/' . $this->objScreening1->screening_id, [
                'seat_id' => 999999
            ]);

        $response->assertSessionHasErrors('seat_id');
    }

    /**
     * Test 24 : Vérifie qu'une place appartenant à une autre salle est refusée.
     */
    public function test_confirm_reservation_refuses_seat_from_another_room()
    {
        $response = $this->actingAs($this->objUser1)
            ->post('/confirm_reservation/' . $this->objScreening1->screening_id, [
                'seat_id' => $this->arSeatsRoom2[0]->seat_id
            ]);

        $response->assertRedirect('/make_reservation/' . $this->objScreening1->screening_id);
    }

    /**
     * Test 25 : Vérifie qu'une place déjà réservée ne peut pas être confirmée.
     */
    public function test_confirm_reservation_refuses_reserved_seat()
    {
        Reservation::create([
            'screening_id' => $this->objScreening1->screening_id,
            'id' => $this->objUser2->id,
            'seat_id' => $this->arSeatsRoom1[0]->seat_id
        ]);

        $response = $this->actingAs($this->objUser1)
            ->post('/confirm_reservation/' . $this->objScreening1->screening_id, [
                'seat_id' => $this->arSeatsRoom1[0]->seat_id
            ]);

        $response->assertRedirect('/make_reservation/' . $this->objScreening1->screening_id);
    }

    /**
     * Test 26 : Vérifie l'affichage du tableau de bord administrateur.
     */
    public function test_get_admin_dashboard(): void
    {
        $response = $this->actingAs($this->objUser2)
            ->get('/admin_dashboard');

        $response->assertOk();
        $response->assertViewIs('pages.admin_dashboard');
        $response->assertViewHas('arScreenings');

        $arScreenings = $response->viewData('arScreenings');

        $this->assertCount(2, $arScreenings);

        $this->assertTrue(
            $arScreenings[0]->screening_date >= $arScreenings[1]->screening_date
        );
    }

    /**
     * Test 27 : Vérifie le calcul du nombre de places occupées et du taux de remplissage.
     */
    public function test_get_admin_dashboard_occupancy_calculation(): void
    {
        // Trois places réservées pour la première séance
        foreach ($this->arSeatsRoom1->take(3) as $objSeat) {
            Reservation::create([
                'screening_id' => $this->objScreening1->screening_id,
                'id' => $this->objUser1->id,
                'seat_id' => $objSeat->seat_id
            ]);
        }

        // Deux places réservées pour la deuxième séance
        foreach ($this->arSeatsRoom2->take(2) as $objSeat) {
            Reservation::create([
                'screening_id' => $this->objScreening2->screening_id,
                'id' => $this->objUser2->id,
                'seat_id' => $objSeat->seat_id
            ]);
        }

        $response = $this->actingAs($this->objUser2)
            ->get('/admin_dashboard');

        $response->assertOk();

        $arScreenings = $response->viewData('arScreenings');

        $objScreening1 = $arScreenings->firstWhere(
            'screening_id',
            $this->objScreening1->screening_id
        );

        $objScreening2 = $arScreenings->firstWhere(
            'screening_id',
            $this->objScreening2->screening_id
        );

        // Vérifie le nombre de places occupées
        $this->assertEquals(3, $objScreening1->occupied_seats);
        $this->assertEquals(2, $objScreening2->occupied_seats);

        // Vérifie les pourcentages de remplissage
        $this->assertEquals(
            round((3 / $this->arSeatsRoom1->count()) * 100, 2),
            $objScreening1->occupancy_rate
        );

        $this->assertEquals(
            round((2 / $this->arSeatsRoom2->count()) * 100, 2),
            $objScreening2->occupancy_rate
        );
    }

    /**
     * Test 28 : Vérifie qu'un utilisateur non-connecté ne puisse pas accéder au tableau de bord.
     */
    public function test_get_admin_dashboard_unauthenticated(): void
    {
        $response = $this->get('/admin_dashboard');

        $response->assertRedirect('/');
    }

    /**
     * Test 29 : Vérifie qu'un utilisateur non-administrateur ne puisse pas accéder au tableau de bord.
     */
    public function test_get_admin_dashboard_unauthorized(): void
    {
        $response = $this->actingAs($this->objUser1)
            ->get('/admin_dashboard');

        $response->assertRedirect('/');
    }

    /**
     * Test 30 : Vérifie la suppression d'une séance par un administrateur.
     */
    public function test_delete_screening()
    {
        $this->actingAs($this->objUser2);

        $response = $this->get('/delete_screening/' . $this->objScreening1->screening_id);

        $response->assertOk();
        $response->assertViewIs('pages.message');
        $response->assertViewHas('strMessage', "La séance a bien été supprimée.");
        $response->assertViewHas('strLink', '/screenings_list');

        $this->assertDatabaseMissing('screenings', [
            'screening_id' => $this->objScreening1->screening_id
        ]);
    }

    /**
     * Test 31 : Vérifie qu'un visiteur non connecté ne peut pas supprimer une séance.
     */
    public function test_delete_screening_unauthenticated()
    {
        $response = $this->get('/delete_screening/' . $this->objScreening1->screening_id);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('screenings', [
            'screening_id' => $this->objScreening1->screening_id
        ]);
    }

    /**
     * Test 32 : Vérifie qu'on ne puisse pas supprimer une séance inexistante.
     */
    public function test_delete_unknown_screening()
    {
        $this->actingAs($this->objUser2);

        $response = $this->get('/delete_screening/999999');

        $response->assertRedirect('/');
    }
}

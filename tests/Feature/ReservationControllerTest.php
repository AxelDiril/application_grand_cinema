<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Genre;
use App\Models\Screening;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReservationControllerTest extends TestCase
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
    private $objUser3;
    private $objRoleU;
    private $objRoleC;
    private $objReservation1;
    private $objReservation2;

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

        // Création des salles
        $this->objRoom1 = Room::create([
            'room_name' => 'Salle Test A',
            'capacity' => 100
        ]);

        $this->objRoom2 = Room::create([
            'room_name' => 'Salle Test B',
            'capacity' => 150
        ]);

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

        // Création du rôle utilisateur
        $this->objRoleU = Role::create([
            'role_code' => 'U',
            'role_label' => 'Utilisateur'
        ]);

        // Création des utilisateurs
        $this->objUser1 = User::create([
            'name' => 'Utilisateur Test A',
            'email' => 'testA@example.com',
            'password' => bcrypt('password'),
            'role_code' => 'U'
        ]);

        $this->objUser2 = User::create([
            'name' => 'Utilisateur Test B',
            'email' => 'testB@example.com',
            'password' => bcrypt('password'),
            'role_code' => 'U'
        ]);

        // Création des réservations
        $this->objReservation1 = Reservation::create([
            'screening_id' => $this->objScreening1->screening_id,
            'id' => $this->objUser1->id,
            'seats_quantity' => 3
        ]);

        $this->objReservation2 = Reservation::create([
            'screening_id' => $this->objScreening2->screening_id,
            'id' => $this->objUser2->id,
            'seats_quantity' => 2
        ]);

        // Création d'un agent de contrôle pour accéder à la page
        $this->objRoleC = Role::create([
            'role_code' => 'C',
            'role_label' => 'Agent de contrôle'
        ]);

        $this->objUser3 = User::create([
            'name' => 'Utilisateur Test C',
            'email' => 'testC@example.com',
            'password' => bcrypt('password'),
            'role_code' => 'C'
        ]);
    }

    /**
     * Test 1 : Vérifie que toutes les réservations sont affichées.
     */
    public function test_get_reservations_list()
    {
        $response = $this->actingAs($this->objUser3)->get('/reservations_list');

        $response->assertOk();
        $response->assertViewIs('pages.reservations_list');

        $response->assertViewHas('arReservations', function ($reservations) {
            return $reservations->contains(
                'reservation_id',
                $this->objReservation1->reservation_id
            )
                && $reservations->contains(
                    'reservation_id',
                    $this->objReservation2->reservation_id
                );
        });
    }

    /**
     * Test 2 : Vérifie que les relations utilisateur, séance,
     * film et salle sont correctement chargées.
     */
    public function test_get_reservations_list_loads_relations()
    {
        $response = $this->actingAs($this->objUser3)->get('/reservations_list');

        $response->assertOk();

        $response->assertViewHas('arReservations', function ($reservations) {
            $reservation = $reservations->firstWhere(
                'reservation_id',
                $this->objReservation1->reservation_id
            );

            return $reservation !== null
                && $reservation->relationLoaded('user')
                && $reservation->relationLoaded('screening')
                && $reservation->screening->relationLoaded('movie')
                && $reservation->screening->relationLoaded('room');
        });
    }

    /**
     * Test 3 : Vérifie le filtre par nom de l'utilisateur.
     */
    public function test_filter_reservations_by_user_name()
    {
        $response = $this->actingAs($this->objUser3)->get(
            '/reservations_list?name=Utilisateur Test A'
        );

        $response->assertOk();

        $response->assertViewHas('arReservations', function ($reservations) {
            return $reservations->count() === 1
                && $reservations->first()->reservation_id
                === $this->objReservation1->reservation_id;
        });
    }

    /**
     * Test 4 : Vérifie le filtre par identifiant de réservation.
     */
    public function test_filter_reservations_by_id()
    {
        $response = $this->actingAs($this->objUser3)->get(
            '/reservations_list?reservation_id='
            . $this->objReservation1->reservation_id
        );

        $response->assertOk();

        $response->assertViewHas('arReservations', function ($reservations) {
            return $reservations->count() === 1
                && $reservations->first()->reservation_id
                === $this->objReservation1->reservation_id;
        });
    }

    /**
     * Test 5 : Vérifie le filtre par date de séance.
     */
    public function test_filter_reservations_by_screening_date()
    {
        $response = $this->actingAs($this->objUser3)->get(
            '/reservations_list?screening_date=2026-12-10'
        );

        $response->assertOk();

        $response->assertViewHas('arReservations', function ($reservations) {
            return $reservations->count() === 1
                && $reservations->first()->reservation_id
                === $this->objReservation1->reservation_id;
        });
    }

    /**
     * Test 6 : Vérifie le filtre par film.
     */
    public function test_filter_reservations_by_movie()
    {
        $response = $this->actingAs($this->objUser3)->get(
            '/reservations_list?movie_id=' . $this->objMovie1->movie_id
        );

        $response->assertOk();

        $response->assertViewHas('arReservations', function ($reservations) {
            return $reservations->count() === 1
                && $reservations->first()->reservation_id
                === $this->objReservation1->reservation_id;
        });
    }

    /**
     * Test 7 : Vérifie le filtre par salle.
     */
    public function test_filter_reservations_by_room()
    {
        $response = $this->actingAs($this->objUser3)->get(
            '/reservations_list?room_id=' . $this->objRoom1->room_id
        );

        $response->assertOk();

        $response->assertViewHas('arReservations', function ($reservations) {
            return $reservations->count() === 1
                && $reservations->first()->reservation_id
                === $this->objReservation1->reservation_id;
        });
    }

    /**
     * Test 8 : Vérifie plusieurs filtres simultanés.
     */
    public function test_filter_reservations_with_multiple_filters()
    {
        $response = $this->actingAs($this->objUser3)->get(
            '/reservations_list?name=Utilisateur Test A'
            . '&reservation_id=' . $this->objReservation1->reservation_id
            . '&screening_date=2026-12-10'
            . '&movie_id=' . $this->objMovie1->movie_id
            . '&room_id=' . $this->objRoom1->room_id
        );

        $response->assertOk();

        $response->assertViewHas('arReservations', function ($reservations) {
            return $reservations->count() === 1
                && $reservations->first()->reservation_id
                === $this->objReservation1->reservation_id;
        });
    }

    /**
     * Test 9 : Vérifie qu'un utilisateur authentifié peut créer une réservation.
     */
    public function test_add_reservation()
    {
        $response = $this->actingAs($this->objUser1)
            ->post('/add_reservation/' . $this->objScreening1->screening_id, [
                'seats_quantity' => 4
            ]);

        $response->assertOk();

        $response->assertViewIs('pages.message');

        $response->assertViewHas(
            'strMessage',
            'Votre réservation a bien été enregistrée. Vous pouvez télécharger votre billet dans la section "Mes billets"'
        );

        $this->assertDatabaseHas('reservations', [
            'screening_id' => $this->objScreening1->screening_id,
            'id' => $this->objUser1->id,
            'seats_quantity' => 4
        ]);
    }

    /**
     * Test 10 : Vérifie qu'un utilisateur non authentifié ne peut pas réserver.
     */
    public function test_add_reservation_unauthenticated()
    {
        $response = $this->post('/add_reservation/' . $this->objScreening1->screening_id, [
            'seats_quantity' => 2
        ]);

        $response->assertRedirect('/');
    }

    /**
     * Test 11 : Vérifie qu'il est impossible de réserver plus de places
     * que la capacité disponible de la salle.
     */
    public function test_add_reservation_too_many_seats()
    {
        $response = $this->actingAs($this->objUser1)
            ->post('/add_reservation/' . $this->objScreening1->screening_id, [
                'seats_quantity' => $this->objRoom1->capacity + 1
            ]);

        $response->assertRedirect(
            '/make_reservation/' . $this->objScreening1->screening_id
        );
    }

    /**
     * Test 12 : Vérifie l'affichage des réservations de l'utilisateur connecté.
     */
    public function test_get_my_tickets(): void
    {
        $response = $this->actingAs($this->objUser1)
            ->get('/my_tickets');

        $response->assertOk();
        $response->assertViewIs('pages.my_tickets');
        $response->assertViewHas('arReservations');

        $arReservations = $response->viewData('arReservations');

        // Vérifie que seule la réservation de l'utilisateur connecté est affichée
        $this->assertCount(1, $arReservations);
        $this->assertEquals(
            $this->objReservation1->reservation_id,
            $arReservations->first()->reservation_id
        );
    }

    /**
     * Test 13 : Vérifie qu'un utilisateur non-connecté puisse accéder aux réservations.
     */
    public function test_get_my_tickets_unauthenticated(): void
    {
        $response = $this->get('/my_tickets');

        $response->assertOk();
        $response->assertViewIs('pages.message');
        $response->assertViewHas(
            'strMessage',
            "Vous devez vous connecter pour consulter vos réservations."
        );
        $response->assertViewHas('strLink', '/login');
        $response->assertViewHas('strLinkMessage', 'Connexion');
    }

    /**
     * Test 14 ! Vérifie que le billet téléchargé correspond bien à l'utilisateur connecté.
     */
    public function test_download_ticket(): void
    {
        $response = $this->actingAs($this->objUser1)
            ->get('/download_ticket/' . $this->objReservation1->reservation_id);

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader(
            'content-disposition',
            'attachment; filename=billet_reservation_' .
            $this->objReservation1->reservation_id . '.pdf'
        );
    }

    /**
     * Test 15 : Vérifie qu'un billet ne puisse pas être téléchargé sans être connecté.
     */
    public function test_download_ticket_unauthenticated(): void
    {
        $response = $this->get(
            '/download_ticket/' . $this->objReservation1->reservation_id
        );

        $response->assertOk();
        $response->assertViewIs('pages.message');
        $response->assertViewHas(
            'strMessage',
            "Vous devez vous connecter pour télécharger vos billets."
        );
        $response->assertViewHas('strLink', '/login');
        $response->assertViewHas('strLinkMessage', 'Connexion');
    }

    /**
     * Test 16 : Vérifie qu'on ne puisse pas télécharger le billet d'une réservation qui n'existe pas
     */
    public function test_download_ticket_unknown_reservation(): void
    {
        $response = $this->actingAs($this->objUser1)
            ->get('/download_ticket/999999');

        $response->assertRedirect('/');
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Screening;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScreeningControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test 1 : Vérifie qu'un film présent dans la table screening apparaisse dans la liste.
     */
    public function test_get_screenings_list()
    {
        $movie = Movie::create([
            'movie_name' => 'Le Film Test',
            'director' => 'Jean Dupont',
            'duration_minutes' => 120,
            'description' => 'Description du film.'
        ]);

        $room = Room::create([
            'room_name' => 'Salle Test',
            'capacity' => 100
        ]);

        $screening = Screening::create([
            'movie_id' => $movie->movie_id,
            'room_id' => $room->room_id,
            'screening_date' => now()->addDay()
        ]);

        $response = $this->get('/screenings_list');

        $response->assertStatus(200);
        $response->assertViewIs('pages.screenings_list');

        $response->assertViewHas('arScreenings', function ($screenings) use ($screening) {
            return $screenings->contains(
                'screening_id',
                $screening->screening_id
            );
        });
    }

    /**
     * Test 2 : Vérifie que la liste est vide lorsqu'il n'y a aucune séance.
     */
    public function test_get_screenings_list_empty()
    {
        $response = $this->get('/screenings_list');

        $response->assertStatus(200);
        $response->assertViewIs('pages.screenings_list');

        $response->assertViewHas('arScreenings', function ($screenings) {
            return $screenings->isEmpty();
        });
    }

    /**
     * Test 3 : Vérifie que les films et la salle de la séance sont corrects.
     */
    public function test_get_screenings_list_loads_movie_and_room()
    {
        $movie = Movie::create([
            'movie_name' => 'Le Film Test',
            'director' => 'Jean Dupont',
            'duration_minutes' => 120,
            'description' => 'Description du film.'
        ]);

        $room = Room::create([
            'room_name' => 'Salle Test',
            'capacity' => 100
        ]);

        Screening::create([
            'movie_id' => $movie->movie_id,
            'room_id' => $room->room_id,
            'screening_date' => now()->addDay()
        ]);

        $response = $this->get('/screenings_list');

        $response->assertStatus(200);

        $response->assertViewHas('arScreenings', function ($screenings) {
            $screening = $screenings->first();

            return $screening !== null
                && $screening->relationLoaded('movie')
                && $screening->relationLoaded('room');
        });
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Movie;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MovieControllerTest extends TestCase
{
    use DatabaseTransactions;

    // Test 1 : afficher la liste des films
    public function test_get_movie_list()
    {
        $movie = Movie::create([
            'movie_name' => 'Le Dîner Impossible',
            'director' => 'Maxime Roux',
            'duration_minutes' => 101,
            'description' => 'Un repas de famille tourne à la catastrophe.'
        ]);

        $response = $this->get('/movies_list');

        $response->assertStatus(200);
        $response->assertViewIs('pages.movies_list');
        $response->assertViewHas('arMovies');

        $this->assertTrue(
            $response->viewData('arMovies')->contains('movie_id', $movie->movie_id)
        );
    }

    // Test 2 : afficher une liste vide
    public function test_get_movie_list_empty()
    {
        $response = $this->get('/movies_list');

        $response->assertStatus(200);
        $response->assertViewIs('pages.movies_list');

        $response->assertViewHas('arMovies', function ($movies) {
            return $movies->isEmpty();
        });
    }

    // Test 3 : afficher un film existant
    public function test_get_movie()
    {
        $movie = Movie::create([
            'movie_name' => 'Les Petits Explorateurs',
            'director' => 'Pauline Chevalier',
            'duration_minutes' => 79,
            'description' => 'Trois enfants partent à la recherche d’un trésor.'
        ]);

        $response = $this->get('/movie/' . $movie->movie_id);

        $response->assertStatus(200);
        $response->assertViewIs('pages.movie');

        $response->assertViewHas('objMovie', function ($objMovie) use ($movie) {
            return $objMovie->movie_id === $movie->movie_id;
        });
    }

    // Test 4 : afficher un message si le film n'existe pas
    public function test_get_movie_unknown()
    {
        $this->assertNull(Movie::find(999999));

        $response = $this->get('/movie/999999');

        $response->assertStatus(200);
        $response->assertViewIs('pages.message');
        $response->assertViewHas('strMessage', "Ce film n'existe pas.");
    }
}
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Movie;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MovieControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test 1 : Vérifier qu'un film présent dans la table movies apparaît dans la liste complète.
     */
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

    /**
     * Test 2 : Vérifier qu'aucun film soit dans la table movies.
     */
    public function test_get_movie_list_empty()
    {
        $response = $this->get('/movies_list');

        $response->assertStatus(200);
        $response->assertViewIs('pages.movies_list');

        $response->assertViewHas('arMovies', function ($movies) {
            return $movies->isEmpty();
        });
    }

    /**
     * Test 3 : Vérifier qu'un film présent dans la table movies puisse être récupéré pour
     * afficher ses détails dans la vue.
     */
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

    /**
     * Test 4 : Vérifier que l'accès à un film qui n'existe pas redirige vers un message d'erreur.
     */
    public function test_get_movie_unknown()
    {
        $this->assertNull(Movie::find(999999));

        $response = $this->get('/movie/999999');

        $response->assertStatus(200);
        $response->assertViewIs('pages.message');
        $response->assertViewHas('strMessage', "Ce film n'existe pas.");
    }
}
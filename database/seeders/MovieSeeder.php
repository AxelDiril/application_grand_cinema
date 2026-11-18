<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        $movies = [
            [
                'movie_id' => 1,
                'movie_name' => 'Le Dernier Voyage',
                'director' => 'Élodie Marchand',
                'duration_minutes' => 118,
                'description' => 'Un astronaute entreprend une mission qui pourrait changer le destin de l’humanité.',
            ],
            [
                'movie_id' => 2,
                'movie_name' => 'Un Été à Paris',
                'director' => 'Thomas Girard',
                'duration_minutes' => 96,
                'description' => 'Deux inconnus se rencontrent au détour des rues de Paris.',
            ],
            [
                'movie_id' => 3,
                'movie_name' => 'Les Ombres du passé',
                'director' => 'Nadia Fontaine',
                'duration_minutes' => 132,
                'description' => 'Une enquêtrice rouvre une affaire classée depuis vingt ans.',
            ],
            [
                'movie_id' => 4,
                'movie_name' => 'Mission Minuit',
                'director' => 'Antoine Lefort',
                'duration_minutes' => 109,
                'description' => null,
            ],
            [
                'movie_id' => 5,
                'movie_name' => 'Le Royaume des lucioles',
                'director' => 'Claire Morel',
                'duration_minutes' => 88,
                'description' => 'Une jeune héroïne découvre un monde caché dans la forêt.',
            ],
            [
                'movie_id' => 6,
                'movie_name' => 'À Contretemps',
                'director' => 'Julien Perrin',
                'duration_minutes' => 104,
                'description' => 'Une famille tente de se reconstruire après un événement inattendu.',
            ],
            [
                'movie_id' => 7,
                'movie_name' => 'Planète Océan',
                'director' => 'Sophie Garnier',
                'duration_minutes' => 91,
                'description' => null,
            ],
            [
                'movie_id' => 8,
                'movie_name' => 'Le Dîner impossible',
                'director' => 'Maxime Roux',
                'duration_minutes' => 101,
                'description' => 'Un repas de famille tourne à la catastrophe.',
            ],
            [
                'movie_id' => 9,
                'movie_name' => 'La Cité de verre',
                'director' => 'Maya Benali',
                'duration_minutes' => 127,
                'description' => 'Dans une ville futuriste, une journaliste découvre un secret inquiétant.',
            ],
            [
                'movie_id' => 10,
                'movie_name' => 'Les Petits Explorateurs',
                'director' => 'Pauline Chevalier',
                'duration_minutes' => 79,
                'description' => 'Trois enfants partent à la recherche d’un trésor.',
            ],
            [
                'movie_id' => 11,
                'movie_name' => 'Dernière danse',
                'director' => 'Romain Gauthier',
                'duration_minutes' => 115,
                'description' => 'Une danseuse prépare son ultime représentation.',
            ],
            [
                'movie_id' => 12,
                'movie_name' => 'Sans étiquette',
                'director' => 'Lina Caron',
                'duration_minutes' => 93,
                'description' => null,
            ],
            [
                'movie_id' => 13,
                'movie_name' => 'Le Mystère du phare',
                'director' => 'Arthur Noël',
                'duration_minutes' => 100,
                'description' => 'Un gardien de phare est confronté à d’étranges phénomènes.',
            ],
            [
                'movie_id' => 14,
                'movie_name' => 'Au fil des saisons',
                'director' => 'Manon Vidal',
                'duration_minutes' => 84,
                'description' => null,
            ],
        ];

        foreach ($movies as $movie) {
            DB::table('movies')->updateOrInsert(
                ['movie_id' => $movie['movie_id']],
                [
                    'movie_name' => $movie['movie_name'],
                    'director' => $movie['director'],
                    'duration_minutes' => $movie['duration_minutes'],
                    'description' => $movie['description'],
                ]
            );
        }
    }
}

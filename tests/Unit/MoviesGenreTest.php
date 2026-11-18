
<?php

use App\Models\MoviesGenre;
use Tests\TestCase;

uses(TestCase::class);

test('la table utilisée est movies_genres', function () {
    $moviesGenre = new MoviesGenre();

    expect($moviesGenre->getTable())->toBe('movies_genres');
});

test('les identifiants movie_id et genre_id sont convertis en entiers', function () {
    $moviesGenre = new MoviesGenre();

    $moviesGenre->movie_id = '5';
    $moviesGenre->genre_id = '2';

    expect($moviesGenre->movie_id)->toBeInt()
        ->and($moviesGenre->genre_id)->toBeInt();
});

test('les identifiants ne sont pas auto-incrémentés', function () {
    $moviesGenre = new MoviesGenre();

    expect($moviesGenre->getIncrementing())->toBeFalse();
});

test('la relation genre est de type belongsTo', function () {
    $moviesGenre = new MoviesGenre();

    expect($moviesGenre->genre())
        ->toBeInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class
        );
});

test('la relation movie est de type belongsTo', function () {
    $moviesGenre = new MoviesGenre();

    expect($moviesGenre->movie())
        ->toBeInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class
        );
});


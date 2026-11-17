
<?php

use App\Models\Movie;
use Tests\TestCase;

uses(TestCase::class);

test('la clé primaire est movie_id', function () {
    $movie = new Movie();

    expect($movie->getKeyName())->toBe('movie_id');
});

test('la table utilisée est movies', function () {
    $movie = new Movie();

    expect($movie->getTable())->toBe('movies');
});

test('la durée du film est convertie en entier', function () {
    $movie = new Movie();

    $movie->duration_minutes = '120';

    expect($movie->duration_minutes)->toBeInt();
});

test('les attributs autorisés en remplissage sont corrects', function () {
    $movie = new Movie();

    expect($movie->getFillable())->toBe([
        'movie_name',
        'director',
        'duration_minutes',
        'description'
    ]);
});

test('la description peut être nulle', function () {
    $movie = new Movie();

    $movie->description = null;

    expect($movie->description)->toBeNull();
});

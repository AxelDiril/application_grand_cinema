
<?php

use App\Models\Genre;
use Tests\TestCase;

uses(TestCase::class);

test('la clé primaire est genre_id', function () {
    $genre = new Genre();

    expect($genre->getKeyName())->toBe('genre_id');
});

test('la table utilisée est genres', function () {
    $genre = new Genre();

    expect($genre->getTable())->toBe('genres');
});

test('les attributs autorisés en remplissage sont corrects', function () {
    $genre = new Genre();

    expect($genre->getFillable())->toBe([
        'genre_label'
    ]);
});

test('la relation movies est de type belongsToMany', function () {
    $genre = new Genre();

    expect($genre->movies())
        ->toBeInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsToMany::class
        );
});

test('le genre peut recevoir un libellé', function () {
    $genre = new Genre();
    $genre->genre_label = 'Science-fiction';

    expect($genre->genre_label)->toBe('Science-fiction');
});

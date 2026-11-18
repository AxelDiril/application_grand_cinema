<?php

use App\Models\Screening;
use Tests\TestCase;

uses(TestCase::class);

test('la clé primaire est screening_id', function () {
    $screening = new Screening();

    expect($screening->getKeyName())->toBe('screening_id');
});

test('la table utilisée est screenings', function () {
    $screening = new Screening();

    expect($screening->getTable())->toBe('screenings');
});

test('la date de séance est convertie en datetime', function () {
    $screening = new Screening();

    $screening->screening_date = '2026-11-17 14:30:00';

    expect($screening->screening_date)
        ->toBeInstanceOf(\Carbon\CarbonImmutable::class);
});

test('les identifiants du film et de la salle sont convertis en entiers', function () {
    $screening = new Screening();

    $screening->room_id = '2';
    $screening->movie_id = '5';

    expect($screening->room_id)->toBeInt()
        ->and($screening->movie_id)->toBeInt();
});

test('les attributs autorisés en remplissage sont corrects', function () {
    $screening = new Screening();

    expect($screening->getFillable())->toBe([
        'screening_date',
        'room_id',
        'movie_id'
    ]);
});

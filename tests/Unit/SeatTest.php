
<?php

use App\Models\Seat;
use Tests\TestCase;

uses(TestCase::class);

test('la clé primaire est seat_id', function () {
    $seat = new Seat();

    expect($seat->getKeyName())->toBe('seat_id');
});

test('la table utilisée est seats', function () {
    $seat = new Seat();

    expect($seat->getTable())->toBe('seats');
});

test('les attributs autorisés en remplissage sont corrects', function () {
    $seat = new Seat();

    expect($seat->getFillable())->toBe([
        'room_id',
        'seat_label'
    ]);
});

test('la relation room est de type belongsTo', function () {
    $seat = new Seat();

    expect($seat->room())
        ->toBeInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class
        );
});

test('la relation reservations est de type hasMany', function () {
    $seat = new Seat();

    expect($seat->reservations())
        ->toBeInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class
        );
});

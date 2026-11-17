
<?php

use App\Models\Reservation;
use Tests\TestCase;

uses(TestCase::class);

test('la clé primaire est reservation_id', function () {
    $reservation = new Reservation();

    expect($reservation->getKeyName())->toBe('reservation_id');
});

test('la table utilisée est reservations', function () {
    $reservation = new Reservation();

    expect($reservation->getTable())->toBe('reservations');
});

test('les attributs numériques sont convertis en entiers', function () {
    $reservation = new Reservation();

    $reservation->seats_quantity = '3';
    $reservation->id = '2';
    $reservation->screening_id = '5';

    expect($reservation->seats_quantity)->toBeInt()
        ->and($reservation->id)->toBeInt()
        ->and($reservation->screening_id)->toBeInt();
});

test('les attributs autorisés en remplissage sont corrects', function () {
    $reservation = new Reservation();

    expect($reservation->getFillable())->toBe([
        'seats_quantity',
        'id',
        'screening_id'
    ]);
});

test('la relation user est de type belongsTo', function () {
    $reservation = new Reservation();

    expect($reservation->user())
        ->toBeInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class
        );
});

test('la relation screening est de type belongsTo', function () {
    $reservation = new Reservation();

    expect($reservation->screening())
        ->toBeInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class
        );
});

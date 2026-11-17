
<?php

use App\Models\Room;
use Tests\TestCase;

uses(TestCase::class);

test('la clé primaire est room_id', function () {
    $room = new Room();

    expect($room->getKeyName())->toBe('room_id');
});

test('la table utilisée est rooms', function () {
    $room = new Room();

    expect($room->getTable())->toBe('rooms');
});

test('la capacité est convertie en entier', function () {
    $room = new Room();
    $room->capacity = '150';

    expect($room->capacity)->toBeInt();
});

test('les attributs autorisés en remplissage sont corrects', function () {
    $room = new Room();

    expect($room->getFillable())->toBe([
        'room_name',
        'capacity'
    ]);
});
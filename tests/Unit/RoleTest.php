
<?php

use App\Models\Role;
use Tests\TestCase;

uses(TestCase::class);

test('la clé primaire est role_code', function () {
    $role = new Role();

    expect($role->getKeyName())->toBe('role_code');
});

test('la clé primaire est de type non auto-incrémenté', function () {
    $role = new Role();

    expect($role->getIncrementing())->toBeFalse();
});

test('la table utilisée est roles', function () {
    $role = new Role();

    expect($role->getTable())->toBe('roles');
});

test('les attributs autorisés en remplissage sont corrects', function () {
    $role = new Role();

    expect($role->getFillable())->toBe([
        'role_code',
        'role_label'
    ]);
});

test('la relation users est de type hasMany', function () {
    $role = new Role();

    expect($role->users())
        ->toBeInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class
        );
});

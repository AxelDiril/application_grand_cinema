
<?php

use App\Models\User;
use Tests\TestCase;

uses(TestCase::class);

test('la table utilisée est users', function () {
    $user = new User();

    expect($user->getTable())->toBe('users');
});

test('la clé primaire est id', function () {
    $user = new User();

    expect($user->getKeyName())->toBe('id');
});

test('les attributs autorisés en remplissage sont corrects', function () {
    $user = new User();

    expect($user->getFillable())->toBe([
        'id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'role_code',
        'comment'
    ]);
});

test('le mot de passe et le remember token sont cachés', function () {
    $user = new User();

    expect($user->getHidden())->toBe([
        'password',
        'remember_token'
    ]);
});

test('la date de vérification de l email est convertie en datetime', function () {
    $user = new User();
    $user->email_verified_at = '2026-11-17 14:30:00';

    expect($user->email_verified_at)
        ->toBeInstanceOf(\Carbon\CarbonImmutable::class);
});

test('le mot de passe utilise le cast hashed', function () {
    $user = new User();
    $user->password = 'mon-mot-de-passe';

    expect($user->password)->not->toBe('mon-mot-de-passe');
});

test('la relation role est de type belongsTo', function () {
    $user = new User();

    expect($user->role())
        ->toBeInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class
        );
});

test('le commentaire peut être nul', function () {
    $user = new User();
    $user->comment = null;

    expect($user->comment)->toBeNull();
});

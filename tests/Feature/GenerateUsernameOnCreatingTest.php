<?php

use Illuminate\Support\Str;
use ZedanLab\Paymob\Tests\App\Models\User;

it('generates a username on creating if it is enabled', function () {
    User::setPaymobOptions(['on_creating' => true]);

    $user = User::factory()->create();
    expect(! is_null($user->username))->toBeTrue();
});

it('generates a unique username on creating', function () {
    User::setPaymobOptions(['on_creating' => true, 'unique' => true, 'source' => 'name']);

    $user = User::factory()->create(['name' => 'John Doe']);
    $anotherUser = User::factory()->create(['name' => 'John Doe']);

    expect($user->username != $anotherUser->username)->toBeTrue();
    expect(Str::startsWith($anotherUser->username, $user->username))->toBeTrue();
});

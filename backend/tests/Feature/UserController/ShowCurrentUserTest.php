<?php

use App\Models\User;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 401 if no user is authenticated', function () {
    $this->getJson('api/user')
        ->assertStatus(Response::HTTP_UNAUTHORIZED);
});

it('should return a user when user is not admin', function () {
    $userFromDB = $this->actingAs($this->user)
        ->getJson('api/user')
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($userFromDB)
        ->id->toBe($this->user->id)
        ->name->toBe($this->user->name);
});

it('should return a user when user is admin', function () {
    $userFromDB = $this->actingAs($this->admin)
        ->getJson('api/user')
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($userFromDB)
        ->id->toBe($this->admin->id)
        ->name->toBe($this->admin->name);
});
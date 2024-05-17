<?php

use App\Models\User;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if no user is authenticated', function () {
    $user = User::factory()->create();

    $this->getJson('/api/users/' . $user->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $user = User::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/users/' . $user->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $user = User::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->getJson('/api/users/' . $user->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return a user when user is not admin', function () {
    $user = User::factory()->create();

    $userFromDB = $this->actingAs($this->user)
        ->getJson('/api/users/' . $user->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($userFromDB)
        ->id->toBe($user->id)
        ->name->toBe($user->name);
});

it('should return a user when user is admin', function () {
    $user = User::factory()->create();

    $userFromDB = $this->actingAs($this->admin)
        ->getJson('/api/users/' . $user->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($userFromDB)
        ->id->toBe($user->id)
        ->name->toBe($user->name);
});
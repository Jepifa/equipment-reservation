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
    User::factory()->count(3)->create();

    $this->getJson('/api/users/other-users')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    User::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/users/other-users')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not validated', function () {
    User::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/users/other-users')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return every users when user is not admin', function () {
    User::factory()->count(3)->create();

    $users = $this->actingAs($this->user)
        ->getJson('/api/users/other-users')
        ->assertStatus(Response::HTTP_OK)
        ->json();
        
    expect($users)->toHaveCount(6);
});

it('should return every users when user is admin', function () {
    User::factory()->count(3)->create();

    $users = $this->actingAs($this->admin)
        ->getJson('/api/users/other-users')
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($users)->toHaveCount(6);
});



<?php

use App\Models\Manip;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if no user is authenticated', function () {
    Manip::factory()->count(3)->create();

    $this->getJson('/api/manips/user')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    Manip::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/manips/user')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not validated', function () {
    Manip::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/manips/user')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return the manips of the admin', function () {
    Manip::factory()->count(3)->create();
    Manip::factory(['user_id' => $this->admin])->count(2)->create();

    $manips = $this->actingAs($this->admin)
        ->getJson('/api/manips/user')
        ->assertStatus(Response::HTTP_OK)
        ->json();
        
    expect($manips)->toHaveCount(2);
});

it('should return the manips of the user', function () {
    Manip::factory()->count(3)->create();
    Manip::factory(['user_id' => $this->user])->count(2)->create();

    $manips = $this->actingAs($this->user)
        ->getJson('/api/manips/user')
        ->assertStatus(Response::HTTP_OK)
        ->json();
        
    expect($manips)->toHaveCount(2);
});


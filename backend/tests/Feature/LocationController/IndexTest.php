<?php

use App\Models\Location;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if no user is authenticated', function () {
    Location::factory()->count(3)->create();

    $this->getJson('/api/locations')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    Location::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/locations')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not validated', function () {
    Location::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/locations')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return every locations when user is not admin', function () {
    Location::factory()->count(3)->create();

    $locations = $this->actingAs($this->user)
        ->getJson('/api/locations')
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($locations)->toHaveCount(3);
});

it('should return every locations when user is admin', function () {
    Location::factory()->count(3)->create();

    $locations = $this->actingAs($this->admin)
        ->getJson('/api/locations')
        ->assertStatus(Response::HTTP_OK)
        ->json();
        
    expect($locations)->toHaveCount(3);
});



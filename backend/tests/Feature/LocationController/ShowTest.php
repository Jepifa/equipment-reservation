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
    $location = Location::factory()->create();

    $this->getJson('/api/locations/' . $location->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $location = Location::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/locations/' . $location->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $location = Location::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->getJson('/api/locations/' . $location->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return a location when user is not admin', function () {
    $location = Location::factory()->create();

    $locationFromDB = $this->actingAs($this->user)
        ->getJson('/api/locations/' . $location->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($locationFromDB)
        ->id->toBe($location->id)
        ->name->toBe($location->name)
        ->siteId->toBe($location->site_id);
});

it('should return a location when user is admin', function () {
    $location = Location::factory()->create();

    $locationFromDB = $this->actingAs($this->admin)
        ->getJson('/api/locations/' . $location->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($locationFromDB)
        ->id->toBe($location->id)
        ->name->toBe($location->name)
        ->siteId->toBe($location->site_id);
});
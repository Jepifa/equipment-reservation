<?php

use App\Models\Location;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if user is not admin', function () {
    $location = Location::factory()->create();

    $this->actingAs($this->user)
        ->deleteJson('/api/locations/' . $location->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $location = Location::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->deleteJson('/api/locations/' . $location->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $location = Location::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->deleteJson('/api/locations/' . $location->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $location = Location::factory()->create();

    $this->deleteJson('/api/locations/' . $location->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should delete a location', function () {
    $location = Location::factory()->create();
 
    $this->actingAs($this->admin)
        ->deleteJson('/api/locations/' . $location->id)
        ->assertStatus(Response::HTTP_NO_CONTENT);
 
    $this->assertDatabaseCount('locations', 0);
    $this->assertDatabaseMissing('locations', $location->toArray());
 });


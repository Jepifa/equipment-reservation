<?php

use App\Models\Preference;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if no user is authenticated', function () {
    Preference::factory()->count(3)->create();

    $this->getJson('/api/preferences')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    Preference::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/preferences')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not validated', function () {
    Preference::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/preferences')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return every preferences when user is not admin', function () {
    Preference::factory()->count(3)->create();

    $preferences = $this->actingAs($this->user)
        ->getJson('/api/preferences')
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($preferences)->toHaveCount(3);
});

it('should return every preferences when user is admin', function () {
    Preference::factory()->count(3)->create();

    $preferences = $this->actingAs($this->admin)
        ->getJson('/api/preferences')
        ->assertStatus(Response::HTTP_OK)
        ->json();
        
    expect($preferences)->toHaveCount(3);
});



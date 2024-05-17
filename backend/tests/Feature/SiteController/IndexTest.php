<?php

use App\Models\Site;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if no user is authenticated', function () {
    Site::factory()->count(3)->create();

    $this->getJson('/api/sites')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    Site::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/sites')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not validated', function () {
    Site::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/sites')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return every sites when user is not admin', function () {
    Site::factory()->count(3)->create();

    $sites = $this->actingAs($this->user)
        ->getJson('/api/sites')
        ->assertStatus(Response::HTTP_OK)
        ->json();
        
    expect($sites)->toHaveCount(3);
});

it('should return every sites when user is admin', function () {
    Site::factory()->count(3)->create();

    $sites = $this->actingAs($this->admin)
        ->getJson('/api/sites')
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($sites)->toHaveCount(3);
});



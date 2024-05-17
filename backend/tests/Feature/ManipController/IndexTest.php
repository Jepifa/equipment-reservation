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

    $this->getJson('/api/manips')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    Manip::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/manips')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not validated', function () {
    Manip::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/manips')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return every manips when user is not admin', function () {
    Manip::factory()->count(3)->create();

    $manips = $this->actingAs($this->user)
        ->getJson('/api/manips')
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($manips)->toHaveCount(3);
});

it('should return every manips when user is admin', function () {
    Manip::factory()->count(3)->create();

    $manips = $this->actingAs($this->admin)
        ->getJson('/api/manips')
        ->assertStatus(Response::HTTP_OK)
        ->json();
        
    expect($manips)->toHaveCount(3);
});



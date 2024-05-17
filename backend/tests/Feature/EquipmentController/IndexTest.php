<?php

use App\Models\Equipment;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if no user is authenticated', function () {
    Equipment::factory()->count(3)->create();

    $this->getJson('/api/equipments')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    Equipment::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/equipments')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not validated', function () {
    Equipment::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/equipments')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return every equipments when user is not admin', function () {
    Equipment::factory()->count(3)->create();

    $equipments = $this->actingAs($this->user)
        ->getJson('/api/equipments')
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($equipments)->toHaveCount(3);
});

it('should return every equipments when user is admin', function () {
    Equipment::factory()->count(3)->create();

    $equipments = $this->actingAs($this->admin)
        ->getJson('/api/equipments')
        ->assertStatus(Response::HTTP_OK)
        ->json();
        
    expect($equipments)->toHaveCount(3);
});



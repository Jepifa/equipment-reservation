<?php

use App\Models\EquipmentGroup;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if no user is authenticated', function () {
    EquipmentGroup::factory()->count(3)->create();

    $this->getJson('/api/equipment-groups')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    EquipmentGroup::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/equipment-groups')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not validated', function () {
    EquipmentGroup::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/equipment-groups')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return every equipment groups when user is not admin', function () {
    EquipmentGroup::factory()->count(3)->create();

    $equipmentGroups = $this->actingAs($this->user)
        ->getJson('/api/equipment-groups')
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($equipmentGroups)->toHaveCount(3);
});

it('should return every equipment groups when user is admin', function () {
    EquipmentGroup::factory()->count(3)->create();

    $equipmentGroups = $this->actingAs($this->admin)
        ->getJson('/api/equipment-groups')
        ->assertStatus(Response::HTTP_OK)
        ->json();
        
    expect($equipmentGroups)->toHaveCount(3);
});



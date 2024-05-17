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
    $equipment = Equipment::factory()->create();

    $this->getJson('/api/equipments/' . $equipment->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $equipment = Equipment::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/equipments/' . $equipment->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $equipment = Equipment::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->getJson('/api/equipments/' . $equipment->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return a equipment when user is not admin', function () {
    $equipment = Equipment::factory()->create();

    $equipmentFromDB = $this->actingAs($this->user)
        ->getJson('/api/equipments/' . $equipment->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($equipmentFromDB)
        ->id->toBe($equipment->id)
        ->name->toBe($equipment->name)
        ->equipmentGroupId->toBe($equipment->equipment_group_id);
});

it('should return a equipment when user is admin', function () {
    $equipment = Equipment::factory()->create();

    $equipmentFromDB = $this->actingAs($this->admin)
        ->getJson('/api/equipments/' . $equipment->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($equipmentFromDB)
        ->id->toBe($equipment->id)
        ->name->toBe($equipment->name)
        ->equipmentGroupId->toBe($equipment->equipment_group_id);
});
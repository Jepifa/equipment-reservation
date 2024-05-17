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
    $equipmentGroup = EquipmentGroup::factory()->create();

    $this->getJson('/api/equipment-groups/' . $equipmentGroup->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $equipmentGroup = EquipmentGroup::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/equipment-groups/' . $equipmentGroup->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $equipmentGroup = EquipmentGroup::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->getJson('/api/equipment-groups/' . $equipmentGroup->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return a equipment group when user is not admin', function () {
    $equipmentGroup = EquipmentGroup::factory()->create();

    $equipmentGroupFromDB = $this->actingAs($this->user)
        ->getJson('/api/equipment-groups/' . $equipmentGroup->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($equipmentGroupFromDB)
        ->id->toBe($equipmentGroup->id)
        ->name->toBe($equipmentGroup->name)
        ->categoryId->toBe($equipmentGroup->category_id);
});

it('should return a equipment group when user is admin', function () {
    $equipmentGroup = EquipmentGroup::factory()->create();

    $equipmentGroupFromDB = $this->actingAs($this->admin)
        ->getJson('/api/equipment-groups/' . $equipmentGroup->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($equipmentGroupFromDB)
        ->id->toBe($equipmentGroup->id)
        ->name->toBe($equipmentGroup->name)
        ->categoryId->toBe($equipmentGroup->category_id);
});
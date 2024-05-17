<?php

use App\Models\EquipmentGroup;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if user is not admin', function () {
    $equipmentGroup = EquipmentGroup::factory()->create();

    $this->actingAs($this->user)
        ->deleteJson('/api/equipment-groups/' . $equipmentGroup->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $equipmentGroup = EquipmentGroup::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->deleteJson('/api/equipment-groups/' . $equipmentGroup->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $equipmentGroup = EquipmentGroup::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->deleteJson('/api/equipment-groups/' . $equipmentGroup->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $equipmentGroup = EquipmentGroup::factory()->create();

    $this->deleteJson('/api/equipment-groups/' . $equipmentGroup->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should delete an equipment group', function () {
    $equipmentGroup = EquipmentGroup::factory()->create();
 
    $this->actingAs($this->admin)
        ->deleteJson('/api/equipment-groups/' . $equipmentGroup->id)
        ->assertStatus(Response::HTTP_NO_CONTENT);
 
    $this->assertDatabaseCount('equipment_groups', 0);
    $this->assertDatabaseMissing('equipment_groups', $equipmentGroup->toArray());
 });


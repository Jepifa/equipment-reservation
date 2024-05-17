<?php

use App\Models\Equipment;
use App\Models\EquipmentGroup;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);

    $this->equipmentGroup = EquipmentGroup::factory()->create();
});

it('should return 422 if name is missing', function ($newName) {
    $equipment = Equipment::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/equipments/' . $equipment->id, [
            'name' => $newName, 
            'equipmentGroupId' => $equipment->equipment_group_id, 
            'operational' => $equipment->operational
        ])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if operational is missing', function ($operational) {
    $equipment = Equipment::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/equipments/' . $equipment->id, [
            'name' => $equipment->name, 
            'equipmentGroupId' => $equipment->equipment_group_id, 
            'operational' => $operational
        ])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if equipmentGroupId is missing', function ($newEquipmentGroupId) {
    $equipment = Equipment::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/equipments/' . $equipment->id, [
            'name' => $equipment->name, 
            'equipmentGroupId' => $newEquipmentGroupId, 
            'operational' => $equipment->operational
        ])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([null]);

it('should return 422 if equipmentGroupId does not exist', function ($newEquipmentGroupId) {
    $equipment = Equipment::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/equipments/' . $equipment->id, [
            'name' => $equipment->name, 
            'equipmentGroupId' => $newEquipmentGroupId, 
            'operational' => $equipment->operational
        ])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([0]);

it('should return 422 if name is too long', function ($newName) {
    $equipment = Equipment::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/equipments/' . $equipment->id, [
            'name' => $newName, 
            'equipmentGroupId' => $equipment->equipment_group_id, 
            'operational' => $equipment->operational
        ])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 403 if user is not admin', function () {
    $equipment = Equipment::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->user)
        ->putJson('/api/equipments/' . $equipment->id, [
            'name' => $newName, 
            'equipmentGroupId' => $equipment->equipment_group_id, 
            'operational' => $equipment->operational
        ])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $equipment = Equipment::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->notValidatedUser)
        ->putJson('/api/equipments/' . $equipment->id, [
            'name' => $newName, 
            'equipmentGroupId' => $equipment->equipment_group_id, 
            'operational' => $equipment->operational
        ])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $equipment = Equipment::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->notValidatedAdmin)
        ->putJson('/api/equipments/' . $equipment->id, [
            'name' => $newName, 
            'equipmentGroupId' => $equipment->equipment_group_id, 
            'operational' => $equipment->operational
        ])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $equipment = Equipment::factory()->create();
    $newName = fake()->text(10);

    $this->putJson('/api/equipments/' . $equipment->id, [
            'name' => $newName, 
            'equipmentGroupId' => $equipment->equipment_group_id, 
            'operational' => $equipment->operational
        ])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should update a equipment name and equipmentGroupId as admin and get it as admin and as user', function () {
    $equipment = Equipment::factory()->create();
    $newName = fake()->text(10);
    $newEquipmentGroup = EquipmentGroup::factory()->create();
    $newOperational = fake()->randomElement([true, false]);

    $this->actingAs($this->admin)
        ->putJson('/api/equipments/' . $equipment->id, [
            'name' => $newName, 
            'equipmentGroupId' => $newEquipmentGroup->id, 
            'operational' => $newOperational
        ])
        ->assertStatus(Response::HTTP_OK);

    $equipmentFromDBAdmin = $this->actingAs($this->admin)
        ->getJson('/api/equipments', ['equipment' => $equipment->id])
        ->json()[0];

    $equipmentFromDBUser = $this->actingAs($this->user)
        ->getJson('/api/equipments', ['equipment' => $equipment->id])
        ->json()[0];
 
    expect($equipmentFromDBAdmin)
        ->name->toBe($newName)
        ->equipmentGroupId->toBe($newEquipmentGroup->id)
        ->operational->toBe($newOperational);

    expect($equipmentFromDBUser)
        ->name->toBe($newName)
        ->equipmentGroupId->toBe($newEquipmentGroup->id)
        ->operational->toBe($newOperational);
 });

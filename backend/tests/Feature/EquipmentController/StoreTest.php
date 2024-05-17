<?php

use App\Models\EquipmentGroup;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);

    $this->equipment = createEquipment();
    $this->equipmentGroup = EquipmentGroup::factory()->create();
});

function createEquipment() {
    $equipmentGroup = EquipmentGroup::factory()->create();
    $equipmentName = fake()->text(10);
    $operational = fake()->randomElement([true, false]);

    $equipment = [
        'name' => $equipmentName,
        'equipmentGroupId' => $equipmentGroup->id,
        'operational' => $operational,
    ];

    return $equipment;
}

it('should return 422 if name is missing', function ($name) {
    $equipment = [
        'name' => $name,
        'equipmentGroupId' => $this->equipmentGroup->id,
        'operational' => fake()->randomElement([true, false]),
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/equipments', $equipment)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if operational is missing', function ($operational) {
    $equipment = [
        'name' => fake()->text(10),
        'equipmentGroupId' => $this->equipmentGroup->id,
        'operational' => $operational,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/equipments', $equipment)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if equipmentGroupId is missing', function ($equipmentGroupId) {
    $equipment = [
        'name' => fake()->text(10),
        'equipmentGroupId' => $equipmentGroupId,
        'operational' => fake()->randomElement([true, false]),
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/equipments', $equipment)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([null]);

it('should return 422 if equipmentGroupId does not exist', function ($equipmentGroupId) {
    $equipment = [
        'name' => fake()->text(10),
        'equipmentGroupId' => $equipmentGroupId,
        'operational' => fake()->randomElement([true, false]),
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/equipments', $equipment)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([0]);

it('should return 422 if name is too long', function ($name) {
    $equipment = [
        'name' => $name,
        'equipmentGroupId' => $this->equipmentGroup->id,
        'operational' => fake()->randomElement([true, false]),
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/equipments', $equipment)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 403 if user is not admin', function () {
    $this->actingAs($this->user)
        ->postJson('/api/equipments', $this->equipment)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $this->actingAs($this->notValidatedUser)
        ->postJson('/api/equipments', $this->equipment)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $this->actingAs($this->notValidatedAdmin)
        ->postJson('/api/equipments', $this->equipment)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $this->postJson('/api/equipments', $this->equipment)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should create a equipment as admin and get it as admin and as user', function () {
    $response = $this->actingAs($this->admin)
        ->postJson('/api/equipments', $this->equipment)
        ->assertStatus(Response::HTTP_CREATED)
        ->json();

    $equipmentFromDBAdmin = $this->actingAs($this->admin)
        ->getJson('/api/equipments', ['equipment' => $response['id']])
        ->json()[0];

    $equipmentFromDBUser = $this->actingAs($this->user)
        ->getJson('/api/equipments', ['equipment' => $response['id']])
        ->json()[0];

    expect($equipmentFromDBAdmin)
        ->id->toBe($response['id'])
        ->name->toBe($this->equipment['name'])
        ->equipmentGroupId->toBe($this->equipment['equipmentGroupId'])
        ->operational->toBe($this->equipment['operational']);

    expect($equipmentFromDBUser)
        ->id->toBe($response['id'])
        ->name->toBe($this->equipment['name'])
        ->equipmentGroupId->toBe($this->equipment['equipmentGroupId'])
        ->operational->toBe($this->equipment['operational']);
});
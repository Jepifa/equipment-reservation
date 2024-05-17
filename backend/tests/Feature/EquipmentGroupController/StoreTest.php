<?php

use App\Models\Category;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);

    $this->equipmentGroup = createEquipmentGroup();
    $this->category = Category::factory()->create();
});

function createEquipmentGroup() {
    $category = Category::factory()->create();
    $equipmentGroupName = fake()->text(10);

    $equipmentGroup = [
        'name' => $equipmentGroupName,
        'categoryId' => $category->id,
    ];

    return $equipmentGroup;
}

it('should return 422 if name is missing', function ($name) {
    $equipmentGroup = [
        'name' => $name,
        'categoryId' => $this->category->id,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/equipment-groups', $equipmentGroup)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if categoryId is missing', function ($categoryId) {
    $equipmentGroup = [
        'name' => fake()->text(10),
        'categoryId' => $categoryId,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/equipment-groups', $equipmentGroup)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([null]);

it('should return 422 if categoryId does not exist', function ($categoryId) {
    $equipmentGroup = [
        'name' => fake()->text(10),
        'categoryId' => $categoryId,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/equipment-groups', $equipmentGroup)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([0]);

it('should return 422 if name is too long', function ($name) {
    $equipmentGroup = [
        'name' => $name,
        'categoryId' => $this->category->id,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/equipment-groups', $equipmentGroup)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 403 if user is not admin', function () {
    $this->actingAs($this->user)
        ->postJson('/api/equipment-groups', $this->equipmentGroup)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $this->actingAs($this->notValidatedUser)
        ->postJson('/api/equipment-groups', $this->equipmentGroup)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $this->actingAs($this->notValidatedAdmin)
        ->postJson('/api/equipment-groups', $this->equipmentGroup)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $this->postJson('/api/equipment-groups', $this->equipmentGroup)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should create a equipment group as admin and get it as admin and as user', function () {
    $response = $this->actingAs($this->admin)
        ->postJson('/api/equipment-groups', $this->equipmentGroup)
        ->assertStatus(Response::HTTP_CREATED)
        ->json();

    $equipmentGroupFromDBAdmin = $this->actingAs($this->admin)
        ->getJson('/api/equipment-groups', ['equipmentGroup' => $response['id']])
        ->json()[0];

    $equipmentGroupFromDBUser = $this->actingAs($this->user)
        ->getJson('/api/equipment-groups', ['equipmentGroup' => $response['id']])
        ->json()[0];

    expect($equipmentGroupFromDBAdmin)
        ->id->toBe($response['id'])
        ->name->toBe($this->equipmentGroup['name'])
        ->categoryId->toBe($this->equipmentGroup['categoryId']);

    expect($equipmentGroupFromDBUser)
        ->id->toBe($response['id'])
        ->name->toBe($this->equipmentGroup['name'])
        ->categoryId->toBe($this->equipmentGroup['categoryId']);
});
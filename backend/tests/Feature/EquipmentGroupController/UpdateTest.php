<?php

use App\Models\EquipmentGroup;
use App\Models\Category;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);

    $this->category = Category::factory()->create();
});

it('should return 422 if name is missing', function ($newName) {
    $equipmentGroup = EquipmentGroup::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/equipment-groups/' . $equipmentGroup->id, ['name' => $newName, 'categoryId' => $equipmentGroup->category_id])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if categoryId is missing', function ($newcategoryId) {
    $equipmentGroup = EquipmentGroup::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/equipment-groups/' . $equipmentGroup->id, ['name' => $equipmentGroup->name, 'categoryId' => $newcategoryId])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([null]);

it('should return 422 if categoryId does not exist', function ($newcategoryId) {
    $equipmentGroup = EquipmentGroup::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/equipment-groups/' . $equipmentGroup->id, ['name' => $equipmentGroup->name, 'categoryId' => $newcategoryId])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([0]);

it('should return 422 if name is too long', function ($newName) {
    $equipmentGroup = EquipmentGroup::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/equipment-groups/' . $equipmentGroup->id, ['name' => $newName, 'categoryId' => $equipmentGroup->category_id])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 403 if user is not admin', function () {
    $equipmentGroup = EquipmentGroup::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->user)
        ->putJson('/api/equipment-groups/' . $equipmentGroup->id, ['name' => $newName, 'categoryId' => $equipmentGroup->category_id])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $equipmentGroup = EquipmentGroup::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->notValidatedUser)
        ->putJson('/api/equipment-groups/' . $equipmentGroup->id, ['name' => $newName, 'categoryId' => $equipmentGroup->category_id])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $equipmentGroup = EquipmentGroup::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->notValidatedAdmin)
        ->putJson('/api/equipment-groups/' . $equipmentGroup->id, ['name' => $newName, 'categoryId' => $equipmentGroup->category_id])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $equipmentGroup = EquipmentGroup::factory()->create();
    $newName = fake()->text(10);

    $this->putJson('/api/equipment-groups/' . $equipmentGroup->id, ['name' => $newName, 'categoryId' => $equipmentGroup->category_id])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should update a equipment group name and categoryId as admin and get it as admin and as user', function () {
    $equipmentGroup = EquipmentGroup::factory()->create();
    $newName = fake()->text(10);
    $newcategory = Category::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/equipment-groups/' . $equipmentGroup->id, ['name' => $newName, 'categoryId' => $newcategory->id])
        ->assertStatus(Response::HTTP_OK);

    $equipmentGroupFromDBAdmin = $this->actingAs($this->admin)
        ->getJson('/api/equipment-groups', ['equipmentGroup' => $equipmentGroup->id])
        ->json()[0];

    $equipmentGroupFromDBUser = $this->actingAs($this->user)
        ->getJson('/api/equipment-groups', ['equipmentGroup' => $equipmentGroup->id])
        ->json()[0];
 
    expect($equipmentGroupFromDBAdmin)
        ->name->toBe($newName)
        ->categoryId->toBe($newcategory->id);

    expect($equipmentGroupFromDBUser)
        ->name->toBe($newName)
        ->categoryId->toBe($newcategory->id);
 });

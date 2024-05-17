<?php

use App\Models\Category;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 422 if name is missing', function ($newName) {
    $category = Category::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/categories/' . $category->id, ['name' => $newName])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if name is too long', function ($newName) {
    $category = Category::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/categories/' . $category->id, ['name' => $newName])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 403 if user is not admin', function () {
    $category = Category::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->user)
        ->putJson('/api/categories/' . $category->id, ['name' => $newName])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $category = Category::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->notValidatedUser)
        ->putJson('/api/categories/' . $category->id, ['name' => $newName])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $category = Category::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->notValidatedAdmin)
        ->putJson('/api/categories/' . $category->id, ['name' => $newName])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $category = Category::factory()->create();
    $newName = fake()->text(10);

    $this->putJson('/api/categories/' . $category->id, ['name' => $newName])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should update a category as admin and get it as admin and as user', function () {
    $category = Category::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->admin)
        ->putJson('/api/categories/' . $category->id, ['name' => $newName])
        ->assertStatus(Response::HTTP_OK);

    $categoryFromDBAdmin = $this->actingAs($this->admin)
        ->getJson('/api/categories', ['category' => $category->id])
        ->json()[0];

    $categoryFromDBUser = $this->actingAs($this->user)
        ->getJson('/api/categories', ['category' => $category->id])
        ->json()[0];
 
    expect($categoryFromDBAdmin)
        ->name->toBe($newName);

    expect($categoryFromDBUser)
        ->name->toBe($newName);
 });

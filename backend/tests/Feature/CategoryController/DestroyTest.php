<?php

use App\Models\Category;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if user is not admin', function () {
    $category = Category::factory()->create();

    $this->actingAs($this->user)
        ->deleteJson('/api/categories/' . $category->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $category = Category::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->deleteJson('/api/categories/' . $category->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $category = Category::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->deleteJson('/api/categories/' . $category->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $category = Category::factory()->create();

    $this->deleteJson('/api/categories/' . $category->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should delete a category', function () {
    $category = Category::factory()->create();
 
    $this->actingAs($this->admin)
        ->deleteJson('/api/categories/' . $category->id)
        ->assertStatus(Response::HTTP_NO_CONTENT);
 
    $this->assertDatabaseCount('categories', 0);
    $this->assertDatabaseMissing('categories', $category->toArray());
 });

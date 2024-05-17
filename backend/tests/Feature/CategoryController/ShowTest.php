<?php

use App\Models\Category;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if no user is authenticated', function () {
    $category = Category::factory()->create();

    $this->getJson('/api/categories/' . $category->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $category = Category::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/categories/' . $category->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $category = Category::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->getJson('/api/categories/' . $category->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return a category when user is not admin', function () {
    $category = Category::factory()->create();

    $categoryFromDB = $this->actingAs($this->user)
        ->getJson('/api/categories/' . $category->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($categoryFromDB)
        ->id->toBe($category->id)
        ->name->toBe($category->name);
});

it('should return a category when user is admin', function () {
    $category = Category::factory()->create();

    $categoryFromDB = $this->actingAs($this->admin)
        ->getJson('/api/categories/' . $category->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($categoryFromDB)
        ->id->toBe($category->id)
        ->name->toBe($category->name);
});
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
    Category::factory()->count(3)->create();

    $this->getJson('/api/categories')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    Category::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/categories')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not validated', function () {
    Category::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/categories')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return every categories when user is not admin', function () {
    Category::factory()->count(3)->create();

    $categories = $this->actingAs($this->user)
        ->getJson('/api/categories')
        ->assertStatus(Response::HTTP_OK)
        ->json();
        
    expect($categories)->toHaveCount(3);
});

it('should return every categories when user is admin', function () {
    Category::factory()->count(3)->create();

    $categories = $this->actingAs($this->admin)
        ->getJson('/api/categories')
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($categories)->toHaveCount(3);
});



<?php

use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);

    $this->category = createCategory();
});

function createCategory() {
    $categoryName = fake()->text(10);

    $category = [
        'name' => $categoryName,
    ];

    return $category;
}

it('should return 422 if name is missing', function ($name) {
    $category = [
        'name' => $name,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/categories', $category)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if name is too long', function ($name) {
    $category = [
        'name' => $name,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/categories', $category)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 403 if user is not admin', function () {
    $this->actingAs($this->user)
        ->postJson('/api/categories', $this->category)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $this->actingAs($this->notValidatedUser)
        ->postJson('/api/categories', $this->category)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $this->actingAs($this->notValidatedAdmin)
        ->postJson('/api/categories', $this->category)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $this->postJson('/api/categories', $this->category)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should create a category as admin and get it as admin and as user', function () {
    $response = $this->actingAs($this->admin)
        ->postJson('/api/categories', $this->category)
        ->assertStatus(Response::HTTP_CREATED)
        ->json();

    $categoryFromDBAdmin = $this->actingAs($this->admin)
        ->getJson('/api/categories', ['category' => $response['id']])
        ->json()[0];

    $categoryFromDBUser = $this->actingAs($this->user)
        ->getJson('/api/categories', ['category' => $response['id']])
        ->json()[0];

    expect($categoryFromDBAdmin)
        ->id->toBe($response['id'])
        ->name->toBe($this->category['name']);

    expect($categoryFromDBUser)
        ->id->toBe($response['id'])
        ->name->toBe($this->category['name']);
});
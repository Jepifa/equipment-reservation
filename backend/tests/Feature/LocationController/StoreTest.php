<?php

use App\Models\Site;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);

    $this->location = createLocation();
    $this->site = Site::factory()->create();
});

function createLocation() {
    $site = Site::factory()->create();
    $locationName = fake()->text(10);

    $location = [
        'name' => $locationName,
        'siteId' => $site->id,
    ];

    return $location;
}

it('should return 422 if name is missing', function ($name) {
    $location = [
        'name' => $name,
        'siteId' => $this->site->id,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/locations', $location)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if siteId is missing', function ($siteId) {
    $location = [
        'name' => fake()->text(10),
        'siteId' => $siteId,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/locations', $location)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([null]);

it('should return 422 if siteId does not exist', function ($siteId) {
    $location = [
        'name' => fake()->text(10),
        'siteId' => $siteId,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/locations', $location)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([0]);

it('should return 422 if name is too long', function ($name) {
    $location = [
        'name' => $name,
        'siteId' => $this->site->id,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/locations', $location)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 403 if user is not admin', function () {
    $this->actingAs($this->user)
        ->postJson('/api/locations', $this->location)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $this->actingAs($this->notValidatedUser)
        ->postJson('/api/locations', $this->location)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $this->actingAs($this->notValidatedAdmin)
        ->postJson('/api/locations', $this->location)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $this->postJson('/api/locations', $this->location)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should create a location as admin and get it as admin and as user', function () {
    $response = $this->actingAs($this->admin)
        ->postJson('/api/locations', $this->location)
        ->assertStatus(Response::HTTP_CREATED)
        ->json();

    $locationFromDBAdmin = $this->actingAs($this->admin)
        ->getJson('/api/locations', ['location' => $response['id']])
        ->json()[0];

    $locationFromDBUser = $this->actingAs($this->user)
        ->getJson('/api/locations', ['location' => $response['id']])
        ->json()[0];

    expect($locationFromDBAdmin)
        ->id->toBe($response['id'])
        ->name->toBe($this->location['name'])
        ->siteId->toBe($this->location['siteId']);

    expect($locationFromDBUser)
        ->id->toBe($response['id'])
        ->name->toBe($this->location['name'])
        ->siteId->toBe($this->location['siteId']);
});
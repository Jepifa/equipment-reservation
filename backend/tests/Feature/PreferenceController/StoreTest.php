<?php

use App\Models\Equipment;
use App\Models\Location;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);

    $this->preference = createPreference();
    $this->location = Location::factory()->create();
    $this->equipmentId = Equipment::factory()->create()->id;
    $this->equipmentIds = Equipment::factory()->count(3)->create()->pluck('id');
});

function createPreference() {
    $preferenceName = fake()->text(10);
    $manipName = fake()->text(10);
    $location = Location::factory()->create();
    $equipments = Equipment::factory()->count(3)->create();

    $preference = [
        'name' => $preferenceName,
        'manipName' => $manipName,
        'locationId' => $location->id,
        'equipmentIds' => $equipments->pluck('id'),
    ];

    return $preference;
}

it('should return 422 if name is missing', function ($name) {
    $preference = [
        'name' => $name,
        'manipName' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/preferences', $preference)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if name is too long', function ($name) {
    $preference = [
        'name' => $name,
        'manipName' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/preferences', $preference)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 422 if manipName is missing', function ($manipName) {
    $preference = [
        'name' => fake()->text(10),
        'manipName' => $manipName,
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/preferences', $preference)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if manipName is too long', function ($manipName) {
    $preference = [
        'name' => fake()->text(10),
        'manipName' => $manipName,
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/preferences', $preference)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 422 if locationId is missing', function ($locationId) {
    $preference = [
        'name' => fake()->text(10),
        'manipName' => fake()->text(10),
        'locationId' => $locationId,
        'equipmentIds' => $this->equipmentIds,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/preferences', $preference)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([null]);

it('should return 422 if locationId does not exist', function ($locationId) {
    $preference = [
        'name' => fake()->text(10),
        'manipName' => fake()->text(10),
        'locationId' => $locationId,
        'equipmentIds' => $this->equipmentIds,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/preferences', $preference)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([0]);

it('should return 422 if equipmentIds is missing', function ($equipmentIds) {
    $preference = [
        'name' => fake()->text(10),
        'manipName' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $equipmentIds,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/preferences', $preference)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([[null], [[]]]);

it('should return 422 if equipmentIds does not exist', function ($equipmentIds) {
    $preference = [
        'name' => fake()->text(10),
        'manipName' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $equipmentIds,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/preferences', $preference)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([[0]]);

it('should return 422 if teamIds does not exist', function ($teamIds) {
    $preference = [
        'name' => fake()->text(10),
        'manipName' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'teamIds' => $teamIds
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/preferences', $preference)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([[[0]]]);

it('should return 422 if teamIds contains user (in actingAs) id', function () {
    $preference = [
        'name' => fake()->text(10),
        'manipName' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'teamIds' => [$this->user->id]
    ];

    $this->actingAs($this->user)
        ->postJson('/api/preferences', $preference)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 422 if teamIds contains userId', function () {
    $preference = [
        'name' => fake()->text(10),
        'manipName' => fake()->text(10),
        'userId' => $this->user->id,
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'teamIds' => [$this->user->id]
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/preferences', $preference)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 422 if userId does not exist', function () {
    $preference = [
        'name' => fake()->text(10),
        'manipName' => fake()->text(10),
        'userId' => 0,
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'teamIds' => [$this->user->id]
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/preferences', $preference)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 403 if user is not admin and not validated', function () {
    $this->actingAs($this->notValidatedUser)
        ->postJson('/api/preferences', $this->preference)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $this->actingAs($this->notValidatedAdmin)
        ->postJson('/api/preferences', $this->preference)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $this->postJson('/api/preferences', $this->preference)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should create a preference as admin and get it as admin', function () {
    $response = $this->actingAs($this->admin)
        ->postJson('/api/preferences', $this->preference)
        ->assertStatus(Response::HTTP_CREATED)
        ->json();

    $preferenceFromDB = $this->actingAs($this->admin)
        ->getJson('/api/preferences', ['preference' => $response['id']])
        ->json()[0];

    expect($preferenceFromDB)
        ->id->toBe($response['id'])
        ->name->toBe($this->preference['name'])
        ->manipName->toBe($this->preference['manipName'])
        ->userId->toBe($this->admin->id)
        ->locationId->toBe($this->preference['locationId'])
        ->equipmentIds->toBe($this->preference['equipmentIds']->toArray())
        ->teamIds->toBe([]);
});

it('should create a preference as user and get it as user', function () {
    $response = $this->actingAs($this->user)
        ->postJson('/api/preferences', $this->preference)
        ->assertStatus(Response::HTTP_CREATED)
        ->json();

    $preferenceFromDB = $this->actingAs($this->user)
        ->getJson('/api/preferences', ['preference' => $response['id']])
        ->json()[0];

    expect($preferenceFromDB)
        ->id->toBe($response['id'])
        ->name->toBe($this->preference['name'])
        ->manipName->toBe($this->preference['manipName'])
        ->userId->toBe($this->user->id)
        ->locationId->toBe($this->preference['locationId'])
        ->equipmentIds->toBe($this->preference['equipmentIds']->toArray())
        ->teamIds->toBe([]);
});
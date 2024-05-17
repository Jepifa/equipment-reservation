<?php

use App\Models\Equipment;
use App\Models\Location;
use App\Models\Preference;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);

    $this->preference = createPreferenceWithEquipmentAndTeam($this->user->id);
});

function createPreferenceWithEquipmentAndTeam($userId) {
    $preference = Preference::factory(['user_id' => $userId])->create();
    $equipments = Equipment::factory()->count(3)->create();
    $team = User::factory()->count(2)->create();

    $preference->equipment()->attach($equipments->pluck('id'));

    $preference->team()->attach($team->pluck('id'));

    return $preference;
}

it('should return 422 if name is missing', function ($name) {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => $name,
        'manipName' => $preference->manip_name,
        'userId' => $preference->user_id,
        'locationId' => $preference->location_id,
        'equipmentIds' => collect($preference->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($preference->team)->pluck('id')->toArray(),
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if name is too long', function ($name) {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => $name,
        'manipName' => $preference->manip_name,
        'userId' => $preference->user_id,
        'locationId' => $preference->location_id,
        'equipmentIds' => collect($preference->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($preference->team)->pluck('id')->toArray(),
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 422 if manipName is missing', function ($manipName) {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => $preference->name,
        'manipName' => $manipName,
        'userId' => $preference->user_id,
        'locationId' => $preference->location_id,
        'equipmentIds' => collect($preference->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($preference->team)->pluck('id')->toArray(),
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if manipName is too long', function ($manipName) {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => $preference->name,
        'manipName' => $manipName,
        'userId' => $preference->user_id,
        'locationId' => $preference->location_id,
        'equipmentIds' => collect($preference->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($preference->team)->pluck('id')->toArray(),
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 422 if locationId is missing', function ($locationId) {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => $preference->name,
        'manipName' => $preference->manip_name,
        'userId' => $preference->user_id,
        'locationId' => $locationId,
        'equipmentIds' => collect($preference->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($preference->team)->pluck('id')->toArray(),
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([null]);

it('should return 422 if locationId does not exist', function ($locationId) {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => $preference->name,
        'manipName' => $preference->manip_name,
        'userId' => $preference->user_id,
        'locationId' => $locationId,
        'equipmentIds' => collect($preference->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($preference->team)->pluck('id')->toArray(),
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([0]);

it('should return 422 if equipmentIds is missing', function ($equipmentIds) {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => $preference->name,
        'manipName' => $preference->manip_name,
        'userId' => $preference->user_id,
        'locationId' => $preference->location_id,
        'equipmentIds' => $equipmentIds,
        'teamIds' => collect($preference->team)->pluck('id')->toArray(),
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([[null], [[]]]);

it('should return 422 if equipmentIds does not exist', function ($equipmentIds) {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => $preference->name,
        'manipName' => $preference->manip_name,
        'userId' => $preference->user_id,
        'locationId' => $preference->location_id,
        'equipmentIds' => $equipmentIds,
        'teamIds' => collect($preference->team)->pluck('id')->toArray(),
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([[0]]);

it('should return 422 if teamIds does not exist', function ($teamIds) {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => $preference->name,
        'manipName' => $preference->manip_name,
        'userId' => $preference->user_id,
        'locationId' => $preference->location_id,
        'equipmentIds' => collect($preference->equipment)->pluck('id')->toArray(),
        'teamIds' => $teamIds,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([[[0]]]);

it('should return 422 if teamIds contains userId', function () {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => $preference->name,
        'manipName' => $preference->manip_name,
        'userId' => $preference->user_id,
        'locationId' => $preference->location_id,
        'equipmentIds' => collect($preference->equipment)->pluck('id')->toArray(),
        'teamIds' => [$this->user->id],
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 422 if userId does not exist', function () {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => $preference->name,
        'manipName' => $preference->manip_name,
        'userId' => $preference->user_id,
        'locationId' => $preference->location_id,
        'equipmentIds' => collect($preference->equipment)->pluck('id')->toArray(),
        'teamIds' => [0],
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 403 if user is not admin and not validated', function () {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => $preference->name,
        'manipName' => $preference->manip_name,
        'userId' => $preference->user_id,
        'locationId' => $preference->location_id,
        'equipmentIds' => collect($preference->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($preference->team)->pluck('id')->toArray(),
    ];

    $this->actingAs($this->notValidatedUser)
        ->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => $preference->name,
        'manipName' => $preference->manip_name,
        'userId' => $preference->user_id,
        'locationId' => $preference->location_id,
        'equipmentIds' => collect($preference->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($preference->team)->pluck('id')->toArray(),
    ];

    $this->actingAs($this->notValidatedAdmin)
        ->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => $preference->name,
        'manipName' => $preference->manip_name,
        'userId' => $preference->user_id,
        'locationId' => $preference->location_id,
        'equipmentIds' => collect($preference->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($preference->team)->pluck('id')->toArray(),
    ];

    $this->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should update a preference as admin and get it as admin', function () {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => fake()->text(10),
        'manipName' => fake()->text(10),
        'userId' => User::factory()->create()->id,
        'locationId' => Location::factory()->create()->id,
        'equipmentIds' => Equipment::factory()->count(3)->create()->pluck('id'),
        'teamIds' => User::factory()->count(2)->create()->pluck('id'),
    ];

    $response = $this->actingAs($this->admin)
        ->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    $preferenceFromDB = $this->actingAs($this->admin)
        ->getJson('/api/preferences', ['preference' => $response['id']])
        ->json()[0];

    expect($preferenceFromDB)
        ->id->toBe($response['id'])
        ->name->toBe($preferenceUpdated['name'])
        ->manipName->toBe($preferenceUpdated['manipName'])
        ->userId->toBe($preferenceUpdated['userId'])
        ->locationId->toBe($preferenceUpdated['locationId'])
        ->equipmentIds->toBe($preferenceUpdated['equipmentIds']->toArray())
        ->teamIds->toBe($preferenceUpdated['teamIds']->toArray());
});

it('should update a preference as user and get it as user', function () {
    $preference = $this->preference;
    $preferenceUpdated = [
        'name' => fake()->text(10),
        'manipName' => fake()->text(10),
        'locationId' => Location::factory()->create()->id,
        'equipmentIds' => Equipment::factory()->count(3)->create()->pluck('id'),
        'teamIds' => User::factory()->count(2)->create()->pluck('id'),
    ];

    $response = $this->actingAs($this->user)
        ->putJson('/api/preferences/' . $preference->id, $preferenceUpdated)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    $preferenceFromDB = $this->actingAs($this->user)
        ->getJson('/api/preferences', ['preference' => $response['id']])
        ->json()[0];

    expect($preferenceFromDB)
        ->id->toBe($response['id'])
        ->name->toBe($preferenceUpdated['name'])
        ->manipName->toBe($preferenceUpdated['manipName'])
        ->userId->toBe($this->user->id)
        ->locationId->toBe($preferenceUpdated['locationId'])
        ->equipmentIds->toBe($preferenceUpdated['equipmentIds']->toArray())
        ->teamIds->toBe($preferenceUpdated['teamIds']->toArray());
});
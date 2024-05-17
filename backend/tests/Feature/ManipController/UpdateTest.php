<?php

use App\Models\Equipment;
use App\Models\Location;
use App\Models\Manip;
use App\Models\User;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);

    $dates = getDates();
    $this->beginDate = $dates[0];
    $this->endDate = $dates[1];

    $this->manip = createManipWithEquipmentAndTeam($this->user->id);
});

function createManipWithEquipmentAndTeam($userId) {
    $manip = Manip::factory(['user_id' => $userId])->create();
    $equipments = Equipment::factory()->count(3)->create();
    $team = User::factory()->count(2)->create();

    $manip->equipment()->attach($equipments->pluck('id'));

    $manip->team()->attach($team->pluck('id'));

    return $manip;
}

it('should return 422 if name is missing', function ($name) {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $manip->begin_date,
        'endDate' => $manip->end_date,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if name is too long', function ($name) {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $manip->begin_date,
        'endDate' => $manip->end_date,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 422 if locationId is missing', function ($locationId) {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $locationId,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $manip->begin_date,
        'endDate' => $manip->end_date,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([null]);

it('should return 422 if locationId does not exist', function ($locationId) {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $locationId,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $manip->begin_date,
        'endDate' => $manip->end_date,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([0]);

it('should return 422 if equipmentIds is missing', function ($equipmentIds) {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => $equipmentIds,
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $manip->begin_date,
        'endDate' => $manip->end_date,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([[null], [[]]]);

it('should return 422 if equipmentIds does not exist', function ($equipmentIds) {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => $equipmentIds,
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $manip->begin_date,
        'endDate' => $manip->end_date,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([[[0]]]);

it('should return 422 if teamIds does not exist', function ($teamIds) {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => $teamIds,
        'beginDate' => $manip->begin_date,
        'endDate' => $manip->end_date,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([[[0]]]);

it('should return 422 if teamIds contains userId', function () {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => [$this->user->id],
        'beginDate' => $manip->begin_date,
        'endDate' => $manip->end_date,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 422 if userId does not exist', function () {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => [0],
        'beginDate' => $manip->begin_date,
        'endDate' => $manip->end_date,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});



it('should return 422 if beginDate is missing', function ($beginDate) {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $beginDate,
        'endDate' => $manip->end_date,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if beginDate is before 7', function () {
    $beginDate = fake()->dateTimeThisMonth();

    $hour = 6;
    $minute = 0;
    $second = 0;

    $beginDate->setTime($hour, $minute, $second);

    $endDate = clone $beginDate;
    $endDate->modify('+2 hour');

    $formattedBeginDate = $beginDate->format('Y-m-d H:i:s');
    $formattedEndDate = $endDate->format('Y-m-d H:i:s');

    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 422 if beginDate is after 19', function () {
    $beginDate = fake()->dateTimeThisMonth();

    $hour = 19;
    $minute = 30;
    $second = 0;

    $beginDate->setTime($hour, $minute, $second);

    $endDate = clone $beginDate;
    $endDate->modify('+14 hour');

    $formattedBeginDate = $beginDate->format('Y-m-d H:i:s');
    $formattedEndDate = $endDate->format('Y-m-d H:i:s');

    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 422 if endDate is before 7', function () {
    $beginDate = fake()->dateTimeThisMonth();

    $hour = 18;
    $minute = 0;
    $second = 0;

    $beginDate->setTime($hour, $minute, $second);

    $endDate = clone $beginDate;
    $endDate->modify('+12 hour');

    $formattedBeginDate = $beginDate->format('Y-m-d H:i:s');
    $formattedEndDate = $endDate->format('Y-m-d H:i:s');

    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 422 if endDate is after 19', function () {
    $beginDate = fake()->dateTimeThisMonth();

    $hour = 17;
    $minute = 30;
    $second = 0;

    $beginDate->setTime($hour, $minute, $second);

    $endDate = clone $beginDate;
    $endDate->modify('+2 hour');

    $formattedBeginDate = $beginDate->format('Y-m-d H:i:s');
    $formattedEndDate = $endDate->format('Y-m-d H:i:s');

    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 422 if endDate is before beginDate', function () {
    $beginDate = fake()->dateTimeThisMonth();

    $hour = 17;
    $minute = 30;
    $second = 0;

    $beginDate->setTime($hour, $minute, $second);

    $endDate = clone $beginDate;
    $endDate->modify('-2 hour');

    $formattedBeginDate = $beginDate->format('Y-m-d H:i:s');
    $formattedEndDate = $endDate->format('Y-m-d H:i:s');

    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 422 if endDate is missing', function ($endDate) {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $manip->beginDate,
        'endDate' => $endDate,
    ];

    $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 403 if user is not admin and not validated', function () {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $manip->begin_date,
        'endDate' => $manip->end_date,
    ];

    $this->actingAs($this->notValidatedUser)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $manip->begin_date,
        'endDate' => $manip->end_date,
    ];

    $this->actingAs($this->notValidatedAdmin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => $manip->name,
        'manipName' => $manip->manip_name,
        'userId' => $manip->user_id,
        'locationId' => $manip->location_id,
        'equipmentIds' => collect($manip->equipment)->pluck('id')->toArray(),
        'teamIds' => collect($manip->team)->pluck('id')->toArray(),
        'beginDate' => $manip->begin_date,
        'endDate' => $manip->end_date,
    ];

    $this->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should update a manip as admin and get it as admin', function () {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => fake()->text(10),
        'manipName' => fake()->text(10),
        'userId' => User::factory()->create()->id,
        'locationId' => Location::factory()->create()->id,
        'equipmentIds' => Equipment::factory()->count(3)->create()->pluck('id'),
        'teamIds' => User::factory()->count(2)->create()->pluck('id'),
        'beginDate' => $this->beginDate,
        'endDate' => $this->endDate,
    ];

    $response = $this->actingAs($this->admin)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    $manipFromDB = $this->actingAs($this->admin)
        ->getJson('/api/manips', ['manip' => $response['id']])
        ->json()[0];

    expect($manipFromDB)
        ->id->toBe($response['id'])
        ->name->toBe($manipUpdated['name'])
        ->userId->toBe($manipUpdated['userId'])
        ->locationId->toBe($manipUpdated['locationId'])
        ->equipmentIds->toBe($manipUpdated['equipmentIds']->toArray())
        ->teamIds->toBe($manipUpdated['teamIds']->toArray())
        ->beginDate->toBe($manipUpdated['beginDate'])
        ->endDate->toBe($manipUpdated['endDate']);
});

it('should update a manip as user and get it as user', function () {
    $manip = $this->manip;
    $manipUpdated = [
        'id' => $manip->id,
        'name' => fake()->text(10),
        'manipName' => fake()->text(10),
        'locationId' => Location::factory()->create()->id,
        'equipmentIds' => Equipment::factory()->count(3)->create()->pluck('id'),
        'teamIds' => User::factory()->count(2)->create()->pluck('id'),
        'beginDate' => $this->beginDate,
        'endDate' => $this->endDate,
    ];

    $response = $this->actingAs($this->user)
        ->putJson('/api/manips/' . $manip->id, $manipUpdated)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    $manipFromDB = $this->actingAs($this->user)
        ->getJson('/api/manips', ['manip' => $response['id']])
        ->json()[0];

    expect($manipFromDB)
        ->id->toBe($response['id'])
        ->name->toBe($manipUpdated['name'])
        ->userId->toBe($this->user->id)
        ->locationId->toBe($manipUpdated['locationId'])
        ->equipmentIds->toBe($manipUpdated['equipmentIds']->toArray())
        ->teamIds->toBe($manipUpdated['teamIds']->toArray())
        ->beginDate->toBe($manipUpdated['beginDate'])
        ->endDate->toBe($manipUpdated['endDate']);
});
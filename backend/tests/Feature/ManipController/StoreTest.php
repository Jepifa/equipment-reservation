<?php

use App\Models\Equipment;
use App\Models\Location;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);

    $dates = getDates();
    $this->beginDate = $dates[0];
    $this->endDate = $dates[1];

    $this->manip = createManip($this->beginDate, $this->endDate);
    $this->location = Location::factory()->create();
    $this->equipmentId = Equipment::factory()->create()->id;
    $this->equipmentIds = Equipment::factory()->count(3)->create()->pluck('id');
});

function getDates() {
    $beginDate = fake()->dateTimeThisMonth();

    $hour = fake()->numberBetween(7, 17);
    $minute = ($hour == 17) ? 0 : fake()->randomElement([0, 30]);
    $second = 0;

    $beginDate->setTime($hour, $minute, $second);

    $endDate = clone $beginDate;
    $endDate->modify('+2 hour');

    $formattedBeginDate = $beginDate->format('Y-m-d H:i:s');
    $formattedEndDate = $endDate->format('Y-m-d H:i:s');

    return [$formattedBeginDate, $formattedEndDate];
}

function createManip($beginDate, $endDate) {
    $manipName = fake()->text(10);
    $location = Location::factory()->create();
    $equipments = Equipment::factory()->count(3)->create();

    $manip = [
        'name' => $manipName,
        'locationId' => $location->id,
        'equipmentIds' => $equipments->pluck('id'),
        'beginDate' => $beginDate,
        'endDate' => $endDate,
    ];

    return $manip;
}

it('should return 422 if name is missing', function ($name) {
    $manip = [
        'name' => $name,
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'beginDate' => $this->beginDate,
        'endDate' => $this->endDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if name is too long', function ($name) {
    $manip = [
        'name' => $name,
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'beginDate' => $this->beginDate,
        'endDate' => $this->endDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 422 if locationId is missing', function ($locationId) {
    $manip = [
        'name' => fake()->text(10),
        'locationId' => $locationId,
        'equipmentIds' => $this->equipmentIds,
        'beginDate' => $this->beginDate,
        'endDate' => $this->endDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([null]);

it('should return 422 if locationId does not exist', function ($locationId) {
    $manip = [
        'name' => fake()->text(10),
        'locationId' => $locationId,
        'equipmentIds' => $this->equipmentIds,
        'beginDate' => $this->beginDate,
        'endDate' => $this->endDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([0]);

it('should return 422 if equipmentIds is missing', function ($equipmentIds) {
    $manip = [
        'name' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $equipmentIds,
        'beginDate' => $this->beginDate,
        'endDate' => $this->endDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([[null], [[]]]);

it('should return 422 if equipmentIds does not exist', function ($equipmentIds) {
    $manip = [
        'name' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $equipmentIds,
        'beginDate' => $this->beginDate,
        'endDate' => $this->endDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([[0]]);

it('should return 422 if teamIds does not exist', function ($teamIds) {
    $manip = [
        'name' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'teamIds' => $teamIds,
        'beginDate' => $this->beginDate,
        'endDate' => $this->endDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([[[0]]]);

it('should return 422 if teamIds contains user (in actingAs) id', function () {
    $manip = [
        'name' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'teamIds' => [$this->user->id],
        'beginDate' => $this->beginDate,
        'endDate' => $this->endDate,
    ];

    $this->actingAs($this->user)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 422 if teamIds contains userId', function () {
    $manip = [
        'name' => fake()->text(10),
        'userId' => $this->user->id,
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'teamIds' => [$this->user->id],
        'beginDate' => $this->beginDate,
        'endDate' => $this->endDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 422 if userId does not exist', function () {
    $manip = [
        'name' => fake()->text(10),
        'userId' => 0,
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'teamIds' => [$this->user->id],
        'beginDate' => $this->beginDate,
        'endDate' => $this->endDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 422 if beginDate is missing', function ($beginDate) {
    $manip = [
        'name' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'teamIds' => [$this->user->id],
        'beginDate' => $beginDate,
        'endDate' => $this->endDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
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

    $manip = [
        'name' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'teamIds' => [$this->user->id],
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
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

    $manip = [
        'name' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'teamIds' => [$this->user->id],
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
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

    $manip = [
        'name' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'teamIds' => [$this->user->id],
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
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

    $manip = [
        'name' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'teamIds' => [$this->user->id],
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
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

    $manip = [
        'name' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'teamIds' => [$this->user->id],
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 422 if endDate is missing', function ($endDate) {
    $manip = [
        'name' => fake()->text(10),
        'locationId' => $this->location->id,
        'equipmentIds' => $this->equipmentIds,
        'teamIds' => [$this->user->id],
        'beginDate' => $this->beginDate,
        'endDate' => $endDate,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if selected recurrence is not null and endRecurrenceDate is null', function ($endRecurrenceDateParam) {
    $location = Location::factory()->create();
    $equipments = Equipment::factory()->count(3)->create();
    $beginDate = fake()->dateTimeThisMonth();

    $hour = 11;
    $minute = 0;
    $second = 0;

    $beginDate->setTime($hour, $minute, $second);

    $endDate = clone $beginDate;
    $endDate->modify('+6 hour');

    $endRecurrenceDate = clone $beginDate;
    $endRecurrenceDate->modify('+6 day');

    $formattedBeginDate = $beginDate->format('Y-m-d H:i:s');
    $formattedEndDate = $endDate->format('Y-m-d H:i:s');
    $formattedEndRecurrenceDate = $endRecurrenceDate->format('Y-m-d');

    $manip = [
        'name' => fake()->text(10),
        'locationId' => $location->id,
        'equipmentIds' => $equipments->pluck('id'),
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
        'selectedRecurrence' => 'weekly',
        'endRecurrenceDate' => $endRecurrenceDateParam,
        'endRecurrenceDateFormatted' => $formattedEndRecurrenceDate,
        'selectedWeeklyRecurrence' => '',
        'multipleDaysRecurrence' => '',
    ];

    $this->actingAs($this->user)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if selected recurrence is not null and endRecurrenceDateFormatted is null', function ($endRecurrenceDateFormatted) {
    $location = Location::factory()->create();
    $equipments = Equipment::factory()->count(3)->create();
    $beginDate = fake()->dateTimeThisMonth();

    $hour = 11;
    $minute = 0;
    $second = 0;

    $beginDate->setTime($hour, $minute, $second);

    $endDate = clone $beginDate;
    $endDate->modify('+6 hour');

    $endRecurrenceDate = clone $beginDate;
    $endRecurrenceDate->modify('+6 day');

    $formattedBeginDate = $beginDate->format('Y-m-d H:i:s');
    $formattedEndDate = $endDate->format('Y-m-d H:i:s');

    $manip = [
        'name' => fake()->text(10),
        'locationId' => $location->id,
        'equipmentIds' => $equipments->pluck('id'),
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
        'selectedRecurrence' => 'weekly',
        'endRecurrenceDate' => $endRecurrenceDate,
        'endRecurrenceDateFormatted' => $endRecurrenceDateFormatted,
        'selectedWeeklyRecurrence' => '',
        'multipleDaysRecurrence' => '',
    ];

    $this->actingAs($this->user)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if selected recurrence is not null and endRecurrenceDateFormatted is before beginDate', function () {
    $location = Location::factory()->create();
    $equipments = Equipment::factory()->count(3)->create();
    $beginDate = fake()->dateTimeThisMonth();

    $hour = 11;
    $minute = 0;
    $second = 0;

    $beginDate->setTime($hour, $minute, $second);

    $endDate = clone $beginDate;
    $endDate->modify('+6 hour');

    $endRecurrenceDate = clone $beginDate;
    $endRecurrenceDate->modify('-6 day');

    $formattedBeginDate = $beginDate->format('Y-m-d H:i:s');
    $formattedEndDate = $endDate->format('Y-m-d H:i:s');
    $formattedEndRecurrenceDate = $endRecurrenceDate->format('Y-m-d');

    $manip = [
        'name' => fake()->text(10),
        'locationId' => $location->id,
        'equipmentIds' => $equipments->pluck('id'),
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
        'selectedRecurrence' => 'weekly',
        'endRecurrenceDate' => $endRecurrenceDate,
        'endRecurrenceDateFormatted' => $formattedEndRecurrenceDate,
        'selectedWeeklyRecurrence' => '',
        'multipleDaysRecurrence' => '',
    ];

    $this->actingAs($this->user)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 422 if selected recurrence is not null is multiple but multipleDaysRecurrence is missing', function ($multipleDaysRecurrence) {
    $location = Location::factory()->create();
    $equipments = Equipment::factory()->count(3)->create();
    $beginDate = fake()->dateTimeThisMonth();

    $hour = 11;
    $minute = 0;
    $second = 0;

    $beginDate->setTime($hour, $minute, $second);

    $endDate = clone $beginDate;
    $endDate->modify('+6 hour');

    $endRecurrenceDate = clone $beginDate;
    $endRecurrenceDate->modify('+6 day');

    $formattedBeginDate = $beginDate->format('Y-m-d H:i:s');
    $formattedEndDate = $endDate->format('Y-m-d H:i:s');
    $formattedEndRecurrenceDate = $endRecurrenceDate->format('Y-m-d');

    $manip = [
        'name' => fake()->text(10),
        'locationId' => $location->id,
        'equipmentIds' => $equipments->pluck('id'),
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
        'selectedRecurrence' => 'weekly',
        'endRecurrenceDate' => $endRecurrenceDate,
        'endRecurrenceDateFormatted' => $formattedEndRecurrenceDate,
        'selectedWeeklyRecurrence' => 'multiple',
        'multipleDaysRecurrence' => $multipleDaysRecurrence,
    ];

    $this->actingAs($this->user)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if selected recurrence is not null is multiple but all values in multipleDaysRecurrence are null', function () {
    $location = Location::factory()->create();
    $equipments = Equipment::factory()->count(3)->create();
    $beginDate = fake()->dateTimeThisMonth();

    $hour = 11;
    $minute = 0;
    $second = 0;

    $beginDate->setTime($hour, $minute, $second);

    $endDate = clone $beginDate;
    $endDate->modify('+6 hour');

    $endRecurrenceDate = clone $beginDate;
    $endRecurrenceDate->modify('+6 day');

    $formattedBeginDate = $beginDate->format('Y-m-d H:i:s');
    $formattedEndDate = $endDate->format('Y-m-d H:i:s');
    $formattedEndRecurrenceDate = $endRecurrenceDate->format('Y-m-d');

    $manip = [
        'name' => fake()->text(10),
        'locationId' => $location->id,
        'equipmentIds' => $equipments->pluck('id'),
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
        'selectedRecurrence' => 'weekly',
        'endRecurrenceDate' => $endRecurrenceDate,
        'endRecurrenceDateFormatted' => $formattedEndRecurrenceDate,
        'selectedWeeklyRecurrence' => 'multiple',
        'multipleDaysRecurrence' => [
            "monday" => false,
            "tuesday" => false,
            "wednesday" => false,
            "thursday" => false,
            "friday" => false
        ],
    ];

    $this->actingAs($this->user)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('should return 403 if user is not admin and not validated', function () {
    $this->actingAs($this->notValidatedUser)
        ->postJson('/api/manips', $this->manip)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $this->actingAs($this->notValidatedAdmin)
        ->postJson('/api/manips', $this->manip)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $this->postJson('/api/manips', $this->manip)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should create a manip as admin and get it as admin', function () {
    $response = $this->actingAs($this->admin)
        ->postJson('/api/manips', $this->manip)
        ->assertStatus(Response::HTTP_CREATED)
        ->json();

    $manipFromDB = $this->actingAs($this->admin)
        ->getJson('/api/manips', ['manip' => $response['id']])
        ->json()[0];

    expect($manipFromDB)
        ->id->toBe($response['id'])
        ->name->toBe($this->manip['name'])
        ->userId->toBe($this->admin->id)
        ->locationId->toBe($this->manip['locationId'])
        ->equipmentIds->toBe($this->manip['equipmentIds']->toArray())
        ->teamIds->toBe([])
        ->beginDate->toBe($this->manip['beginDate'])
        ->endDate->toBe($this->manip['endDate']);
});

it('should create a manip as user and get it as user', function () {
    $response = $this->actingAs($this->user)
        ->postJson('/api/manips', $this->manip)
        ->assertStatus(Response::HTTP_CREATED)
        ->json();

    $manipFromDB = $this->actingAs($this->user)
        ->getJson('/api/manips', ['manip' => $response['id']])
        ->json()[0];

    expect($manipFromDB)
        ->id->toBe($response['id'])
        ->name->toBe($this->manip['name'])
        ->userId->toBe($this->user->id)
        ->locationId->toBe($this->manip['locationId'])
        ->equipmentIds->toBe($this->manip['equipmentIds']->toArray())
        ->teamIds->toBe([])
        ->beginDate->toBe($this->manip['beginDate'])
        ->endDate->toBe($this->manip['endDate']);
});

it('should create 5 manips as user using daily recurrence', function () {
    $location = Location::factory()->create();
    $equipments = Equipment::factory()->count(3)->create();
    $beginDate = fake()->dateTimeThisMonth();

    $hour = 11;
    $minute = 0;
    $second = 0;

    $beginDate->setTime($hour, $minute, $second);

    $endDate = clone $beginDate;
    $endDate->modify('+6 hour');

    $endRecurrenceDate = clone $beginDate;
    $endRecurrenceDate->modify('+6 day');

    $formattedBeginDate = $beginDate->format('Y-m-d H:i:s');
    $formattedEndDate = $endDate->format('Y-m-d H:i:s');
    $formattedEndRecurrenceDate = $endRecurrenceDate->format('Y-m-d');

    $manip = [
        'name' => fake()->text(10),
        'locationId' => $location->id,
        'equipmentIds' => $equipments->pluck('id'),
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
        'selectedRecurrence' => 'daily',
        'endRecurrenceDate' => $formattedEndRecurrenceDate,
        'endRecurrenceDateFormatted' => $formattedEndRecurrenceDate,
        'selectedWeeklyRecurrence' => '',
        'multipleDaysRecurrence' => '',
    ];

    $this->actingAs($this->user)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_CREATED)
        ->json();

    $manips = $this->actingAs($this->user)
        ->getJson('/api/manips')
        ->assertStatus(Response::HTTP_OK)
        ->json();
        
    expect($manips)->toHaveCount(5);
});

it('should create 5 manips as user using weekly recurrence one day per week', function () {
    $location = Location::factory()->create();
    $equipments = Equipment::factory()->count(3)->create();
    $beginDate = fake()->dateTimeThisMonth();

    $hour = 11;
    $minute = 0;
    $second = 0;

    $beginDate->setTime($hour, $minute, $second);

    $endDate = clone $beginDate;
    $endDate->modify('+6 hour');

    $endRecurrenceDate = clone $beginDate;
    $endRecurrenceDate->modify('+28 day');

    $formattedBeginDate = $beginDate->format('Y-m-d H:i:s');
    $formattedEndDate = $endDate->format('Y-m-d H:i:s');
    $formattedEndRecurrenceDate = $endRecurrenceDate->format('Y-m-d');

    $manip = [
        'name' => fake()->text(10),
        'locationId' => $location->id,
        'equipmentIds' => $equipments->pluck('id'),
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
        'selectedRecurrence' => 'weekly',
        'endRecurrenceDate' => $formattedEndRecurrenceDate,
        'endRecurrenceDateFormatted' => $formattedEndRecurrenceDate,
        'selectedWeeklyRecurrence' => '',
        'multipleDaysRecurrence' => '',
    ];

    $this->actingAs($this->user)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_CREATED)
        ->json();

    $manips = $this->actingAs($this->admin)
        ->getJson('/api/manips')
        ->assertStatus(Response::HTTP_OK)
        ->json();
        
    expect($manips)->toHaveCount(5);
});

it('should create 5 manips as user using weekly recurrence multiple days per week', function () {
    $location = Location::factory()->create();
    $equipments = Equipment::factory()->count(3)->create();
    $beginDate = fake()->dateTimeThisMonth();

    $hour = 11;
    $minute = 0;
    $second = 0;

    $beginDate->setTime($hour, $minute, $second);

    $endDate = clone $beginDate;
    $endDate->modify('+6 hour');

    $endRecurrenceDate = clone $beginDate;
    $endRecurrenceDate->modify('+6 day');

    $formattedBeginDate = $beginDate->format('Y-m-d H:i:s');
    $formattedEndDate = $endDate->format('Y-m-d H:i:s');
    $formattedEndRecurrenceDate = $endRecurrenceDate->format('Y-m-d');

    $manip = [
        'name' => fake()->text(10),
        'locationId' => $location->id,
        'equipmentIds' => $equipments->pluck('id'),
        'beginDate' => $formattedBeginDate,
        'endDate' => $formattedEndDate,
        'selectedRecurrence' => 'weekly',
        'endRecurrenceDate' => $formattedEndRecurrenceDate,
        'endRecurrenceDateFormatted' => $formattedEndRecurrenceDate,
        'selectedWeeklyRecurrence' => 'multiple',
        'multipleDaysRecurrence' => [
            "monday" => true,
            "tuesday" => true,
            "wednesday" => true,
            "thursday" => true,
            "friday" => true
        ],
    ];

    $this->actingAs($this->user)
        ->postJson('/api/manips', $manip)
        ->assertStatus(Response::HTTP_CREATED)
        ->json();

    $manips = $this->actingAs($this->user)
        ->getJson('/api/manips')
        ->assertStatus(Response::HTTP_OK)
        ->json();
        
    expect($manips)->toHaveCount(5);
});
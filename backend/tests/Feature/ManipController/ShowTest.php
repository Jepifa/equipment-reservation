<?php

use App\Models\Equipment;
use App\Models\Manip;
use App\Models\User;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if no user is authenticated', function () {
    $manip = Manip::factory()->create();

    $this->getJson('/api/manips/' . $manip->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $manip = Manip::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/manips/' . $manip->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $manip = Manip::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->getJson('/api/manips/' . $manip->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return a manip when user is not admin', function () {
    $manip = Manip::factory()->create();
    $equipments = Equipment::factory()->count(3)->create();
    $team = User::factory()->count(2)->create();

    $manip->equipment()->attach($equipments->pluck('id'));

    $manip->team()->attach($team->pluck('id'));

    $manipFromDB = $this->actingAs($this->user)
        ->getJson('/api/manips/' . $manip->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($manipFromDB)
        ->id->toBe($manip->id)
        ->name->toBe($manip->name)
        ->userId->toBe($manip->user_id)
        ->locationId->toBe($manip->location_id)
        ->equipmentIds->toBe($equipments->pluck('id')->toArray())
        ->teamIds->toBe($team->pluck('id')->toArray())
        ->beginDate->toBe($manip->begin_date)
        ->endDate->toBe($manip->end_date);
});

it('should return a manip when user is admin', function () {
    $manip = Manip::factory()->create();
    $equipments = Equipment::factory()->count(3)->create();
    $team = User::factory()->count(2)->create();

    $manip->equipment()->attach($equipments->pluck('id'));

    $manip->team()->attach($team->pluck('id'));

    $manipFromDB = $this->actingAs($this->admin)
        ->getJson('/api/manips/' . $manip->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($manipFromDB)
        ->id->toBe($manip->id)
        ->name->toBe($manip->name)
        ->userId->toBe($manip->user_id)
        ->locationId->toBe($manip->location_id)
        ->equipmentIds->toBe($equipments->pluck('id')->toArray())
        ->teamIds->toBe($team->pluck('id')->toArray())
        ->beginDate->toBe($manip->begin_date)
        ->endDate->toBe($manip->end_date);
});

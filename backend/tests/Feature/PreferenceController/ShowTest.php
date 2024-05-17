<?php

use App\Models\Equipment;
use App\Models\Preference;
use App\Models\User;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if no user is authenticated', function () {
    $preference = Preference::factory()->create();

    $this->getJson('/api/preferences/' . $preference->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $preference = Preference::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/preferences/' . $preference->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $preference = Preference::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->getJson('/api/preferences/' . $preference->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return a preference when user is not admin', function () {
    $preference = Preference::factory()->create();
    $equipments = Equipment::factory()->count(3)->create();
    $team = User::factory()->count(2)->create();

    $preference->equipment()->attach($equipments->pluck('id'));

    $preference->team()->attach($team->pluck('id'));

    $preferenceFromDB = $this->actingAs($this->user)
        ->getJson('/api/preferences/' . $preference->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($preferenceFromDB)
        ->id->toBe($preference->id)
        ->name->toBe($preference->name)
        ->manipName->toBe($preference->manip_name)
        ->userId->toBe($preference->user_id)
        ->locationId->toBe($preference->location_id)
        ->equipmentIds->toBe($equipments->pluck('id')->toArray())
        ->teamIds->toBe($team->pluck('id')->toArray());
});

it('should return a preference when user is admin', function () {
    $preference = Preference::factory()->create();
    $equipments = Equipment::factory()->count(3)->create();
    $team = User::factory()->count(2)->create();

    $preference->equipment()->attach($equipments->pluck('id'));

    $preference->team()->attach($team->pluck('id'));

    $preferenceFromDB = $this->actingAs($this->admin)
        ->getJson('/api/preferences/' . $preference->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($preferenceFromDB)
        ->id->toBe($preference->id)
        ->name->toBe($preference->name)
        ->manipName->toBe($preference->manip_name)
        ->userId->toBe($preference->user_id)
        ->locationId->toBe($preference->location_id)
        ->equipmentIds->toBe($equipments->pluck('id')->toArray())
        ->teamIds->toBe($team->pluck('id')->toArray());
});

<?php

use App\Models\Preference;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if no user is authenticated', function () {
    Preference::factory()->count(3)->create();

    $this->getJson('/api/preferences/user')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    Preference::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/preferences/user')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not validated', function () {
    Preference::factory()->count(3)->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/preferences/user')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return the preferences of the admin', function () {
    Preference::factory()->count(3)->create();
    Preference::factory(['user_id' => $this->admin])->count(2)->create();

    $preferences = $this->actingAs($this->admin)
        ->getJson('/api/preferences/user')
        ->assertStatus(Response::HTTP_OK)
        ->json();
        
    expect($preferences)->toHaveCount(2);
});

it('should return the preferences of the user', function () {
    Preference::factory()->count(3)->create();
    Preference::factory(['user_id' => $this->user])->count(2)->create();

    $preferences = $this->actingAs($this->user)
        ->getJson('/api/preferences/user')
        ->assertStatus(Response::HTTP_OK)
        ->json();
        
    expect($preferences)->toHaveCount(2);
});


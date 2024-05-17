<?php

use App\Models\Site;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if no user is authenticated', function () {
    $site = Site::factory()->create();

    $this->getJson('/api/sites/' . $site->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $site = Site::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->getJson('/api/sites/' . $site->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $site = Site::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->getJson('/api/sites/' . $site->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return a site when user is not admin', function () {
    $site = Site::factory()->create();

    $siteFromDB = $this->actingAs($this->user)
        ->getJson('/api/sites/' . $site->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($siteFromDB)
        ->id->toBe($site->id)
        ->name->toBe($site->name);
});

it('should return a site when user is admin', function () {
    $site = Site::factory()->create();

    $siteFromDB = $this->actingAs($this->admin)
        ->getJson('/api/sites/' . $site->id)
        ->assertStatus(Response::HTTP_OK)
        ->json();

    expect($siteFromDB)
        ->id->toBe($site->id)
        ->name->toBe($site->name);
});
<?php

use App\Models\Site;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if user is not admin', function () {
    $site = Site::factory()->create();

    $this->actingAs($this->user)
        ->deleteJson('/api/sites/' . $site->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $site = Site::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->deleteJson('/api/sites/' . $site->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $site = Site::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->deleteJson('/api/sites/' . $site->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $site = Site::factory()->create();

    $this->deleteJson('/api/sites/' . $site->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should delete a site', function () {
    $site = Site::factory()->create();
 
    $this->actingAs($this->admin)
        ->deleteJson('/api/sites/' . $site->id)
        ->assertStatus(Response::HTTP_NO_CONTENT);
 
    $this->assertDatabaseCount('sites', 0);
    $this->assertDatabaseMissing('sites', $site->toArray());
 });

<?php

use App\Models\Location;
use App\Models\Site;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);

    $this->site = Site::factory()->create();
});

it('should return 422 if name is missing', function ($newName) {
    $location = Location::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/locations/' . $location->id, ['name' => $newName, 'siteId' => $location->site_id])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if siteId is missing', function ($newSiteId) {
    $location = Location::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/locations/' . $location->id, ['name' => $location->name, 'siteId' => $newSiteId])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([null]);

it('should return 422 if siteId does not exist', function ($newSiteId) {
    $location = Location::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/locations/' . $location->id, ['name' => $location->name, 'siteId' => $newSiteId])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with([0]);

it('should return 422 if name is too long', function ($newName) {
    $location = Location::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/locations/' . $location->id, ['name' => $newName, 'siteId' => $location->site_id])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 403 if user is not admin', function () {
    $location = Location::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->user)
        ->putJson('/api/locations/' . $location->id, ['name' => $newName, 'siteId' => $location->site_id])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $location = Location::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->notValidatedUser)
        ->putJson('/api/locations/' . $location->id, ['name' => $newName, 'siteId' => $location->site_id])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $location = Location::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->notValidatedAdmin)
        ->putJson('/api/locations/' . $location->id, ['name' => $newName, 'siteId' => $location->site_id])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $location = Location::factory()->create();
    $newName = fake()->text(10);

    $this->putJson('/api/locations/' . $location->id, ['name' => $newName, 'siteId' => $location->site_id])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should update a location name and siteId as admin and get it as admin and as user', function () {
    $location = Location::factory()->create();
    $newName = fake()->text(10);
    $newSite = Site::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/locations/' . $location->id, ['name' => $newName, 'siteId' => $newSite->id])
        ->assertStatus(Response::HTTP_OK);

    $locationFromDBAdmin = $this->actingAs($this->admin)
        ->getJson('/api/locations', ['location' => $location->id])
        ->json()[0];

    $locationFromDBUser = $this->actingAs($this->user)
        ->getJson('/api/locations', ['location' => $location->id])
        ->json()[0];
 
    expect($locationFromDBAdmin)
        ->name->toBe($newName)
        ->siteId->toBe($newSite->id);

    expect($locationFromDBUser)
        ->name->toBe($newName)
        ->siteId->toBe($newSite->id);
 });

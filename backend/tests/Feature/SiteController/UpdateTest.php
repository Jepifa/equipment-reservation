<?php

use App\Models\Site;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 422 if name is missing', function ($newName) {
    $site = Site::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/sites/' . $site->id, ['name' => $newName])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if name is too long', function ($newName) {
    $site = Site::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/sites/' . $site->id, ['name' => $newName])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 403 if user is not admin', function () {
    $site = Site::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->user)
        ->putJson('/api/sites/' . $site->id, ['name' => $newName])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $site = Site::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->notValidatedUser)
        ->putJson('/api/sites/' . $site->id, ['name' => $newName])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $site = Site::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->notValidatedAdmin)
        ->putJson('/api/sites/' . $site->id, ['name' => $newName])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $site = Site::factory()->create();
    $newName = fake()->text(10);

    $this->putJson('/api/sites/' . $site->id, ['name' => $newName])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should update a site as admin and get it as admin and as user', function () {
    $site = Site::factory()->create();
    $newName = fake()->text(10);

    $this->actingAs($this->admin)
        ->putJson('/api/sites/' . $site->id, ['name' => $newName])
        ->assertStatus(Response::HTTP_OK);

    $siteFromDBAdmin = $this->actingAs($this->admin)
        ->getJson('/api/sites', ['site' => $site->id])
        ->json()[0];

    $siteFromDBUser = $this->actingAs($this->user)
        ->getJson('/api/sites', ['site' => $site->id])
        ->json()[0];
 
    expect($siteFromDBAdmin)
        ->name->toBe($newName);

    expect($siteFromDBUser)
        ->name->toBe($newName);
 });

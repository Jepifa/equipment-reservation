<?php

use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);

    $this->site = createSite();
});

function createSite() {
    $siteName = fake()->text(10);

    $site = [
        'name' => $siteName,
    ];

    return $site;
}

it('should return 422 if name is missing', function ($name) {
    $site = [
        'name' => $name,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/sites', $site)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 422 if name is too long', function ($name) {
    $site = [
        'name' => $name,
    ];

    $this->actingAs($this->admin)
        ->postJson('/api/sites', $site)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['adgaoigzoignzoaoigenaoingezoaoziengoianzegoianaoigzengoianzoegnoiaznegoizngoianegaenozignaiegbsaoinsongdos']);

it('should return 403 if user is not admin', function () {
    $this->actingAs($this->user)
        ->postJson('/api/sites', $this->site)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $this->actingAs($this->notValidatedUser)
        ->postJson('/api/sites', $this->site)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $this->actingAs($this->notValidatedAdmin)
        ->postJson('/api/sites', $this->site)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $this->postJson('/api/sites', $this->site)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should create a site as admin and get it as admin and as user', function () {
    $response = $this->actingAs($this->admin)
        ->postJson('/api/sites', $this->site)
        ->assertStatus(Response::HTTP_CREATED)
        ->json();

    $siteFromDBAdmin = $this->actingAs($this->admin)
        ->getJson('/api/sites', ['site' => $response['id']])
        ->json()[0];

    $siteFromDBUser = $this->actingAs($this->user)
        ->getJson('/api/sites', ['site' => $response['id']])
        ->json()[0];

    expect($siteFromDBAdmin)
        ->id->toBe($response['id'])
        ->name->toBe($this->site['name']);

    expect($siteFromDBUser)
        ->id->toBe($response['id'])
        ->name->toBe($this->site['name']);
});
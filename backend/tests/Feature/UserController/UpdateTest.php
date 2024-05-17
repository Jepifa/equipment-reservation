<?php

use App\Models\User;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 422 if name is missing', function ($newName) {
    $user = User::factory()->create();

    $this->actingAs($this->admin)
        ->putJson('/api/users/' . $user->id, ['name' => $newName, 'email' => $user->email, 'validated' => $user->validated, 'role' => $user->role])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
})->with(['', null]);

it('should return 403 if user is not admin', function () {
    $user = User::factory()->create();
    $newName = fake()->text(10);
    $newEmail = fake()->email();

    $this->actingAs($this->user)
        ->putJson('/api/users/' . $user->id, ['name' => $newName, 'email' => $newEmail, 'validated' => $user->validated, 'role' => $user->role])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $user = User::factory()->create();
    $newName = fake()->text(10);
    $newEmail = fake()->email();

    $this->actingAs($this->notValidatedUser)
        ->putJson('/api/users/' . $user->id, ['name' => $newName, 'email' => $newEmail, 'validated' => $user->validated, 'role' => $user->role])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $user = User::factory()->create();
    $newName = fake()->text(10);
    $newEmail = fake()->email();

    $this->actingAs($this->notValidatedAdmin)
        ->putJson('/api/users/' . $user->id, ['name' => $newName, 'email' => $newEmail, 'validated' => $user->validated, 'role' => $user->role])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $user = User::factory()->create();
    $newName = fake()->text(10);
    $newEmail = fake()->email();

    $this->putJson('/api/users/' . $user->id, ['name' => $newName, 'email' => $newEmail, 'validated' => $user->validated, 'role' => $user->role])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should update a user as admin and get it as admin and as user', function () {
    $user = User::factory()->create();
    $newName = fake()->text(10);
    $newEmail = fake()->email();
    $newValidated = !$user->validated;

    $this->actingAs($this->admin)
        ->putJson('/api/users/' . $user->id, ['name' => $newName, 'email' => $newEmail, 'validated' => $newValidated, 'role' => ['admin']])
        ->assertStatus(Response::HTTP_OK);

    $userFromDBAdmin = $this->actingAs($this->admin)
        ->getJson('/api/users/' . $user->id)
        ->json();

    $userFromDBUser = $this->actingAs($this->user)
        ->getJson('/api/users/' . $user->id)
        ->json();
 
    expect($userFromDBAdmin)
        ->name->toBe($newName)
        ->email->toBe($newEmail)
        ->validated->toBe($newValidated)
        ->role->toBe(['admin']);

    expect($userFromDBUser)
        ->name->toBe($newName)
        ->email->toBe($newEmail)
        ->validated->toBe($newValidated)
        ->role->toBe(['admin']);
 });

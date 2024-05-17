<?php

use App\Models\User;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if user is not admin', function () {
    $user = User::factory()->create();

    $this->actingAs($this->user)
        ->deleteJson('/api/users/' . $user->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $user = User::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->deleteJson('/api/users/' . $user->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $user = User::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->deleteJson('/api/users/' . $user->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $user = User::factory()->create();

    $this->deleteJson('/api/users/' . $user->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should delete a user', function () {
    $user = User::factory()->create();
 
    $this->actingAs($this->admin)
        ->deleteJson('/api/users/' . $user->id)
        ->assertStatus(Response::HTTP_NO_CONTENT);
 
    $this->assertDatabaseCount('users', 4);
    $this->assertDatabaseMissing('users', $user->toArray());
 });

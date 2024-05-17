<?php

use App\Models\Manip;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if user is not admin and the manip is not his own', function () {
    $manip = Manip::factory()->create();

    $this->actingAs($this->user)
        ->deleteJson('/api/manips/' . $manip->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $manip = Manip::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->deleteJson('/api/manips/' . $manip->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $manip = Manip::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->deleteJson('/api/manips/' . $manip->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $manip = Manip::factory()->create();

    $this->deleteJson('/api/manips/' . $manip->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should delete a manip if user is admin', function () {
    $manip = Manip::factory()->create();
 
    $this->actingAs($this->admin)
        ->deleteJson('/api/manips/' . $manip->id)
        ->assertStatus(Response::HTTP_NO_CONTENT);
 
    $this->assertDatabaseCount('manips', 0);
    $this->assertDatabaseMissing('manips', $manip->toArray());
 });

 it('should delete a manip if the manip is that of the user', function () {
     $manip = Manip::factory(['user_id' => $this->user->id])->create();
  
     $this->actingAs($this->user)
         ->deleteJson('/api/manips/' . $manip->id)
         ->assertStatus(Response::HTTP_NO_CONTENT);
  
     $this->assertDatabaseCount('manips', 0);
     $this->assertDatabaseMissing('manips', $manip->toArray());
  });


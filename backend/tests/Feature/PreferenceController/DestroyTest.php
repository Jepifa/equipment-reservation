<?php

use App\Models\Preference;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if user is not admin and the preference is not his own', function () {
    $preference = Preference::factory()->create();

    $this->actingAs($this->user)
        ->deleteJson('/api/preferences/' . $preference->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $preference = Preference::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->deleteJson('/api/preferences/' . $preference->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $preference = Preference::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->deleteJson('/api/preferences/' . $preference->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $preference = Preference::factory()->create();

    $this->deleteJson('/api/preferences/' . $preference->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should delete a preference if user is admin', function () {
    $preference = Preference::factory()->create();
 
    $this->actingAs($this->admin)
        ->deleteJson('/api/preferences/' . $preference->id)
        ->assertStatus(Response::HTTP_NO_CONTENT);
 
    $this->assertDatabaseCount('preferences', 0);
    $this->assertDatabaseMissing('preferences', $preference->toArray());
 });

 it('should delete a preference if the preference is that of the user', function () {
     $preference = Preference::factory(['user_id' => $this->user->id])->create();
  
     $this->actingAs($this->user)
         ->deleteJson('/api/preferences/' . $preference->id)
         ->assertStatus(Response::HTTP_NO_CONTENT);
  
     $this->assertDatabaseCount('preferences', 0);
     $this->assertDatabaseMissing('preferences', $preference->toArray());
  });


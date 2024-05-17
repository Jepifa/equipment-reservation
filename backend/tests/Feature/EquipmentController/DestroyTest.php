<?php

use App\Models\Equipment;
use Illuminate\Http\Response;

beforeEach(function() {
    $this->user = createUser();
    $this->admin = createUser(isAdmin: true);
    $this->notValidatedUser = createUser(isValidated: false);
    $this->notValidatedAdmin = createUser(isAdmin: true, isValidated: false);
});

it('should return 403 if user is not admin', function () {
    $equipment = Equipment::factory()->create();

    $this->actingAs($this->user)
        ->deleteJson('/api/equipments/' . $equipment->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is not admin and not validated', function () {
    $equipment = Equipment::factory()->create();

    $this->actingAs($this->notValidatedUser)
        ->deleteJson('/api/equipments/' . $equipment->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if user is admin but not validated', function () {
    $equipment = Equipment::factory()->create();

    $this->actingAs($this->notValidatedAdmin)
        ->deleteJson('/api/equipments/' . $equipment->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should return 403 if no user is authenticated', function () {
    $equipment = Equipment::factory()->create();

    $this->deleteJson('/api/equipments/' . $equipment->id)
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('should delete a equipment', function () {
    $equipment = Equipment::factory()->create();
 
    $this->actingAs($this->admin)
        ->deleteJson('/api/equipments/' . $equipment->id)
        ->assertStatus(Response::HTTP_NO_CONTENT);
 
    $this->assertDatabaseCount('equipment', 0);
    $this->assertDatabaseMissing('equipment', $equipment->toArray());
 });


<?php

use App\Team;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['password' => bcrypt('test')]);
});

test('a user can create teams', function () {
    Sanctum::actingAs($this->user);

    $response = $this->postJson('/api/teams', [
        'name' => 'My team',
    ]);

    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => __('teams.created'),
            'team' => [
                'name' => 'My team',
            ],
        ]);

    $this->assertDatabaseHas('teams', [
        'user_id' => $this->user->id,
        'name' => 'My team',
    ]);
});

test('a user cant create teams without a name', function () {
    Sanctum::actingAs($this->user);

    $response = $this->postJson('/api/teams');

    $response
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => ['name'],
        ]);

    $this->assertDatabaseMissing('teams', [
        'user_id' => $this->user->id,
    ]);
});

test('a guest cant create teams', function () {
    $response = $this->postJson('/api/teams', [
        'name' => 'My team',
    ]);

    $response->assertStatus(401);

    $this->assertDatabaseMissing('teams', [
        'name' => 'My team',
    ]);
});

test('a user can delete their team', function () {
    Sanctum::actingAs($this->user);

    $team = Team::factory()->create(['user_id' => $this->user->id]);

    $response = $this->deleteJson("/api/teams/{$team->id}");

    $response
        ->assertStatus(200)
        ->assertExactJson([
            'message' => __('teams.deleted'),
        ]);

    $this->assertSoftDeleted('teams', $team->toArray());
});

test('a user cant delete other teams', function () {
    Sanctum::actingAs($this->user);

    $otherUser = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->deleteJson("/api/teams/{$team->id}");

    $response->assertStatus(403);

    $this->assertDatabaseHas('teams', $team->toArray());
});

test('a guest cant delete teams', function () {
    $team = Team::factory()->create();

    $response = $this->deleteJson("/api/teams/{$team->id}");

    $response->assertStatus(401);

    $this->assertDatabaseHas('teams', $team->toArray());
});

test('a user can get all their teams', function () {
    Sanctum::actingAs($this->user);

    $teams = Team::factory()->count(10)->create(['user_id' => $this->user->id]);

    $response = $this->getJson('/api/teams');

    $response
        ->assertStatus(200)
        ->assertJson(['teams' => $teams->toArray()]);
});

test('a user can only get their teams', function () {
    Sanctum::actingAs($this->user);

    $teams = Team::factory()->count(10)->create(['user_id' => $this->user->id]);

    $otherUser = User::factory()->create();
    $otherTeams = Team::factory()->count(10)->create(['user_id' => $otherUser->id]);

    $response = $this->getJson('/api/teams');

    $response->assertJsonMissing(['teams' => $otherTeams->toArray()]);
});

test('guests cant get teams', function () {
    $teams = Team::factory()->count(10)->create(['user_id' => $this->user->id]);

    $response = $this->getJson('/api/teams');

    $response->assertStatus(401);
});

test('a user can update their team', function () {
    Sanctum::actingAs($this->user);

    $team = Team::factory()->create(['user_id' => $this->user->id]);

    $response = $this->putJson("/api/teams/{$team->id}", [
        'name' => 'Changed',
    ]);

    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => __('teams.updated'),
            'team' => [
                'name' => 'Changed',
            ],
        ]);
});

test('a user cant update their team without a name', function () {
    Sanctum::actingAs($this->user);

    $team = Team::factory()->create(['user_id' => $this->user->id]);

    $response = $this->putJson("/api/teams/{$team->id}");

    $response
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => ['name'],
        ]);

    $this->assertDatabaseHas('teams', $team->toArray());
});

test('a user cant update other teams', function () {
    Sanctum::actingAs($this->user);

    $otherUser = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->putJson("/api/teams/{$team->id}", [
        'name' => 'Changed',
    ]);

    $response->assertStatus(403);

    $this->assertDatabaseMissing('teams', [
        'name' => 'Changed',
    ]);
});

test('a guest cant update teams', function () {
    $team = Team::factory()->create();

    $response = $this->putJson("/api/teams/{$team->id}", [
        'name' => 'Changed',
    ]);

    $response->assertStatus(401);

    $this->assertDatabaseMissing('teams', [
        'name' => 'Changed',
    ]);
});

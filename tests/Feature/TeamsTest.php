<?php

use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['password' => bcrypt('test')]);
});

test('a user can create teams', function () {
    Sanctum::actingAs($this->user);

    $players = Player::factory()->count(11)->create(['user_id' => $this->user->id])->pluck('id');

    $response = $this->postJson('/api/teams', [
        'name' => 'My team',
        'players' => $players,
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

    $test = $players->map(function ($id) use ($response) {
        return [
            'team_id' => '1',
            'player_id' => (string) $id,
        ];
    })->all();

    $this->assertDatabaseCount('team_player', 11);
});

test('team requires a name', function () {
    Sanctum::actingAs($this->user);

    $players = Player::factory()->count(11)->create(['user_id' => $this->user->id])->pluck('id');

    $response = $this->postJson('/api/teams', [
        'players' => $players,
    ]);

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

test('team must have eleven players', function () {
    Sanctum::actingAs($this->user);

    $response = $this->postJson('/api/teams', [
        'name' => 'My team',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => ['players'],
        ]);

    $players = Player::factory()->count(4)->create(['user_id' => $this->user->id])->pluck('id');

    $response = $this->postJson('/api/teams', [
        'name' => 'My team',
        'players' => $players,
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => ['players'],
        ]);

    $players = Player::factory()->count(12)->create(['user_id' => $this->user->id])->pluck('id');

    $response = $this->postJson('/api/teams', [
        'name' => 'My team',
        'players' => $players,
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => ['players'],
        ]);

    $this->assertDatabaseMissing('teams', [
        'user_id' => $this->user->id,
    ]);

    $this->assertDatabaseCount('team_player', 0);
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
    $this->withoutExceptionHandling();

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

test('a user can update their team name', function () {
    Sanctum::actingAs($this->user);

    $team = Team::factory()->create(['user_id' => $this->user->id]);

    $players = Player::factory()->count(11)->create(['user_id' => $this->user->id])->pluck('id');

    $response = $this->putJson("/api/teams/{$team->id}", [
        'name' => 'Changed',
        'players' => $players,
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

test('a user can update their team players', function () {
    Sanctum::actingAs($this->user);

    $players = Player::factory()->count(11)->create(['user_id' => $this->user->id])->pluck('id');

    $team = Team::factory()->create(['user_id' => $this->user->id]);
    $team->players()->attach($players);

    $oldPlayers = $team->players->pluck('id');

    $newPlayers = Player::factory()->count(11)->create(['user_id' => $this->user->id])->pluck('id');

    $response = $this->putJson("/api/teams/{$team->id}", [
        'name' => 'Changed',
        'players' => $newPlayers,
    ]);

    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => __('teams.updated'),
            'team' => [
                'name' => 'Changed',
            ],
        ]);

    $this->assertNotEquals($oldPlayers, $newPlayers);

    $this->assertEquals($oldPlayers, $team->players->pluck('id'));

    $this->assertDatabaseCount('team_player', 11);
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

test('a user cant update their team without exacly eleven players', function () {
    Sanctum::actingAs($this->user);

    $team = Team::factory()->create(['user_id' => $this->user->id]);
    $players = Player::factory()->count(20)->create(['user_id' => $this->user->id])->pluck('id');
    $team->players()->attach($players->take(11));

    $response = $this->putJson("/api/teams/{$team->id}", [
        'name' => $team->name,
        'players' => $players->take(4),
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => ['players'],
        ]);

    $response = $this->putJson("/api/teams/{$team->id}", [
        'name' => $team->name,
        'players' => $players->take(12),
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => ['players'],
        ]);
});

test('a user cant update other teams', function () {
    Sanctum::actingAs($this->user);

    $otherUser = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $otherUser->id]);

    $players = Player::factory()->count(11)->create(['user_id' => $this->user->id])->pluck('id');

    $response = $this->putJson("/api/teams/{$team->id}", [
        'name' => 'Changed',
        'players' => $players,
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

test('a user can add a player to their team', function () {
    $this->withoutExceptionHandling();

    Sanctum::actingAs($this->user);

    $team = Team::factory()->create();

    $player = Player::factory()->create(['user_id' => $this->user->id]);

    $response = $this->postJson("/api/teams/{$team->id}/players", [
        'player' => $player->id,
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('team_player', [
        'player_id' => $player->id,
    ]);
});

test('a user can remove a player from their team', function () {
    $this->withoutExceptionHandling();

    Sanctum::actingAs($this->user);

    $team = Team::factory()->create();

    $player = Player::factory()->create(['user_id' => $this->user->id]);

    $team->players()->attach($player->id);

    $response = $this->deleteJson("/api/teams/{$team->id}/players", [
        'player' => $player->id,
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseMissing('team_player', [
        'player_id' => $player->id,
    ]);
});

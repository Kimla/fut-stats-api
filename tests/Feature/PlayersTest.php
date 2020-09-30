<?php

use App\Player;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['password' => bcrypt('test')]);
});

test('a user can create players', function () {
    Sanctum::actingAs($this->user);

    $response = $this->postJson('/api/players', [
        'name' => 'Messi',
    ]);

    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => __('players.created'),
            'player' => [
                'name' => 'Messi',
            ],
        ]);

    $this->assertDatabaseHas('players', [
        'user_id' => $this->user->id,
        'name' => 'Messi',
    ]);
});

test('a user cant create players without a name', function () {
    Sanctum::actingAs($this->user);

    $response = $this->postJson('/api/players');

    $response
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => ['name'],
        ]);

    $this->assertDatabaseMissing('players', [
        'user_id' => $this->user->id,
    ]);
});

test('a guest cant create players', function () {
    $response = $this->postJson('/api/players', [
        'name' => 'Messi',
    ]);

    $response->assertStatus(401);

    $this->assertDatabaseMissing('players', [
        'name' => 'Messi',
    ]);
});

test('a user can delete their player', function () {
    Sanctum::actingAs($this->user);

    $player = Player::factory()->create(['user_id' => $this->user->id]);

    $response = $this->deleteJson("/api/players/{$player->id}");

    $response
        ->assertStatus(200)
        ->assertExactJson([
            'message' => __('players.deleted'),
        ]);

    $this->assertSoftDeleted('players', $player->toArray());
});

test('a user cant delete other players', function () {
    Sanctum::actingAs($this->user);

    $otherUser = User::factory()->create();
    $player = Player::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->deleteJson("/api/players/{$player->id}");

    $response->assertStatus(403);

    $this->assertDatabaseHas('players', $player->toArray());
});

test('a guest cant delete players', function () {
    $player = Player::factory()->create();

    $response = $this->deleteJson("/api/players/{$player->id}");

    $response->assertStatus(401);

    $this->assertDatabaseHas('players', $player->toArray());
});

test('a user can get all their players', function () {
    Sanctum::actingAs($this->user);

    $players = Player::factory()->count(10)->create(['user_id' => $this->user->id]);

    $response = $this->getJson('/api/players');

    $response
        ->assertStatus(200)
        ->assertJson(['players' => $players->toArray()]);
});

test('a user can only get their players', function () {
    Sanctum::actingAs($this->user);

    $players = Player::factory()->count(10)->create(['user_id' => $this->user->id]);

    $otherUser = User::factory()->create();
    $otherPlayers = Player::factory()->count(10)->create(['user_id' => $otherUser->id]);

    $response = $this->getJson('/api/players');

    $response->assertJsonMissing(['players' => $otherPlayers->toArray()]);
});

test('guests cant get players', function () {
    $players = Player::factory()->count(10)->create(['user_id' => $this->user->id]);

    $response = $this->getJson('/api/players');

    $response->assertStatus(401);
});

test('a user can update their player', function () {
    Sanctum::actingAs($this->user);

    $player = Player::factory()->create(['user_id' => $this->user->id]);

    $response = $this->putJson("/api/players/{$player->id}", [
        'name' => 'Changed',
    ]);

    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => __('players.updated'),
            'player' => [
                'name' => 'Changed',
            ],
        ]);
});

test('a user cant update their player without a name', function () {
    Sanctum::actingAs($this->user);

    $player = Player::factory()->create(['user_id' => $this->user->id]);

    $response = $this->putJson("/api/players/{$player->id}");

    $response
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => ['name'],
        ]);

    $this->assertDatabaseHas('players', $player->toArray());
});

test('a user cant update other players', function () {
    Sanctum::actingAs($this->user);

    $otherUser = User::factory()->create();
    $player = Player::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->putJson("/api/players/{$player->id}", [
        'name' => 'Changed',
    ]);

    $response->assertStatus(403);

    $this->assertDatabaseMissing('players', [
        'name' => 'Changed',
    ]);
});

test('a guest cant update players', function () {
    $player = Player::factory()->create();

    $response = $this->putJson("/api/players/{$player->id}", [
        'name' => 'Changed',
    ]);

    $response->assertStatus(401);

    $this->assertDatabaseMissing('players', [
        'name' => 'Changed',
    ]);
});

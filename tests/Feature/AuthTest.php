<?php

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['password' => bcrypt('test')]);
});

test('can get csrf-cookie', function () {
    get('/api/sanctum/csrf-cookie')->assertStatus(204);
});

test('can login', function () {
    $this->withoutExceptionHandling();

    $response = postJson('/api/auth/login', [
        'email' => $this->user->email,
        'password' => 'test',
    ]);

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'message',
        'user',
        'token',
    ]);
});

test('cant login with wrong password', function () {
    $response = postJson('/api/auth/login', [
        'email' => $this->user->email,
        'password' => 'wrong',
    ]);

    $response
        ->assertStatus(422)
        ->assertJson([
            'message' => __('auth.failed'),
        ]);
});

test('cant login with wrong email', function () {
    $response = postJson('/api/auth/login', [
        'email' => 'wrong@wrong.se',
        'password' => 'test',
    ]);

    $response
        ->assertStatus(422)
        ->assertJson([
            'message' => __('auth.failed'),
        ]);
});

test('can register a new account', function () {
    $response = postJson('/api/auth/register', [
        'name' => 'My name',
        'email' => 'new@user.se',
        'password' => 'my-password',
    ]);

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'message',
        'user',
        'token',
    ]);
});

test('name is required when registering a new account', function () {
    $response = postJson('/api/auth/register', [
        'email' => 'new@user.se',
        'password' => 'my-password',
    ]);

    $response->assertStatus(422);

    $response->assertJsonStructure([
        'message',
        'errors' => ['name'],
    ]);
});

test('email is required when registering a new account', function () {
    $response = postJson('/api/auth/register', [
        'name' => 'My name',
        'password' => 'my-password',
    ]);

    $response->assertStatus(422);

    $response->assertJsonStructure([
        'message',
        'errors' => ['email'],
    ]);
});

test('password is required when registering a new account', function () {
    $response = postJson('/api/auth/register', [
        'email' => 'new@user.se',
        'name' => 'My name',
    ]);

    $response->assertStatus(422);

    $response->assertJsonStructure([
        'message',
        'errors' => ['password'],
    ]);
});

test('password is required to be atleast 6 characters when registering a new account', function () {
    $response = postJson('/api/auth/register', [
        'name' => 'My name',
        'email' => 'new@user.se',
        'password' => '12345',
    ]);

    $response->assertStatus(422);

    $response->assertJsonStructure([
        'message',
        'errors' => ['password'],
    ]);

    $response = postJson('/api/auth/register', [
        'name' => 'My name',
        'email' => 'new@user.se',
        'password' => '123456',
    ])->assertStatus(200);
});

test('cant register new account with allready used email', function () {
    $response = postJson('/api/auth/register', [
        'name' => 'My name',
        'email' => $this->user->email,
        'password' => '123456',
    ]);

    $response->assertStatus(422);

    $response->assertJsonStructure([
        'message',
        'errors' => ['email'],
    ]);
});

test('can logout', function () {
    $response = postJson('/api/auth/logout');

    $response
        ->assertStatus(200)
        ->assertJson([
            'message' => __('auth.logout'),
        ]);
});

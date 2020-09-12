<?php

use function Pest\Laravel\{get, post};
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['password' => bcrypt('test')]);
});

test('can get csrf-cookie', function () {
    get('/api/sanctum/csrf-cookie')->assertStatus(204);
});

test('can login', function () {
     post('/api/auth/login', [
        'email' => $this->user->email,
        'password' => 'test'
    ])->assertStatus(200);
});

test('cant login with wrong password', function () {
     post('/api/auth/login', [
        'email' => $this->user->email,
        'password' => 'wrong'
    ])->assertStatus(400);
});

test('cant login with wrong email', function () {
     post('/api/auth/login', [
        'email' => 'wrong@wrong.se',
        'password' => 'test'
    ])->assertStatus(400);
});
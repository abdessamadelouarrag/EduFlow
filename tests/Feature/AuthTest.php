<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('registers a student with interests and returns a jwt token', function (): void {
    $response = $this->postJson('/api/signup', [
        'name' => 'Student One',
        'email' => 'student@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'student',
        'interests' => ['web', 'mobile'],
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath('user.role', 'student')
        ->assertJsonStructure(['access_token', 'user' => ['id', 'interests']]);

    expect(User::first()->interests)->toHaveCount(2);
});

it('logs in and returns a jwt token', function (): void {
    User::factory()->create([
        'email' => 'teacher@example.com',
        'password' => Hash::make('password123'),
        'role' => 'teacher',
    ]);

    $this->postJson('/api/login', [
        'email' => 'teacher@example.com',
        'password' => 'password123',
    ])->assertOk()->assertJsonStructure(['access_token', 'user']);
});

it('resets a password with a broker token', function (): void {
    $user = User::factory()->create([
        'email' => 'reset@example.com',
        'password' => Hash::make('old-password'),
        'role' => 'student',
    ]);

    $token = $this->postJson('/api/forgot-password', [
        'email' => $user->email,
    ])->assertOk()->json('reset_token');

    $this->postJson('/api/reset-password', [
        'email' => $user->email,
        'token' => $token,
        'password' => 'new-password123',
        'password_confirmation' => 'new-password123',
    ])->assertOk();

    expect(Hash::check('new-password123', $user->fresh()->password))->toBeTrue();
});

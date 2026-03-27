<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\InterestRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private readonly InterestRepositoryInterface $interestRepository
    ) {
    }

    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        if ($user->role === 'student') {
            $this->interestRepository->syncUserInterests($user->id, $data['interests'] ?? []);
        }

        $token = auth('api')->login($user);

        return [
            'user' => $user->load('interests'),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];
    }

    public function login(array $credentials): array
    {
        if (! $token = auth('api')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Email or password is incorrect.'],
            ]);
        }

        return [
            'user' => auth('api')->user()->loadMissing('interests'),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];
    }

    public function logout(): void
    {
        auth('api')->logout();
    }

    public function sendResetLink(string $email): array
    {
        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            return [
                'status' => Password::INVALID_USER,
                'token' => null,
            ];
        }

        $token = Password::broker()->createToken($user);

        return [
            'status' => Password::RESET_LINK_SENT,
            'token' => $token,
        ];
    }

    public function resetPassword(array $data): string
    {
        return Password::reset(
            $data,
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );
    }
}

<?php
// app/Services/AuthService.php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService
{
    public function register(array $data): User
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }

    public function login(array $credentials): ?array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return null;
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function requestPasswordReset(string $email): ?string
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return null;
        }

        return $user->generateResetToken();
    }

    public function resetPassword(array $data): bool
    {
        $user = User::where('email', $data['email'])
                    ->where('reset_token', $data['token'])
                    ->first();

        if (!$user || !$user->isResetTokenValid()) {
            return false;
        }

        $user->update([
            'password' => $data['password'],
        ]);

        $user->clearResetToken();

        return true;
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
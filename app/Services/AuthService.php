<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class AuthService
{
    public function check(): bool
    {
        return Session::has('api_token') && Session::has('user');
    }

    public function user(): ?array
    {
        return Session::get('user');
    }

    public function id(): ?int
    {
        return Session::get('user')['id'] ?? null;
    }

    public function name(): string
    {
        return Session::get('user')['name'] ?? 'Гость';
    }

    public function email(): string
    {
        return Session::get('user')['email'] ?? '';
    }

    public function role(): string
    {
        return Session::get('user')['role'] ?? 'guest';
    }

    public function isAdmin(): bool
    {
        return $this->role() === 'admin';
    }

    public function setUser(array $user): void
    {
        Session::put('user', $user);
    }

    public function setToken(string $token): void
    {
        Session::put('api_token', $token);
    }

    public function logout(): void
    {
        Session::forget(['api_token', 'user']);
    }
}

<?php
namespace App\Services;

class AuthService
{
    /**
     * @throws \Exception
     */
    public function login(array $data): string
    {
        $token = auth()->attempt($data);

        if (!$token) {
            @throw new \Exception('Invalid credentials');
        }

        return $token;
    }
}
<?php
namespace App\Service;

use App\Repository\UserRepository;

class AuthService
{
    public function __construct(protected UserRepository $userRepo){}

    public function login($data): string
    {
        return $this->userRepo->login($data);
    }
}
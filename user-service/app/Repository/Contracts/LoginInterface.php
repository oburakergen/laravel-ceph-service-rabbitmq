<?php

namespace App\Repository\Contracts;

interface LoginInterface
{
    /*
     * Login user
     * @param array $data
     * @return string
     */
    public function login(array $data): string;
}
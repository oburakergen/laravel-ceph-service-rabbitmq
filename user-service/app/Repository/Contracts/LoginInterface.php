<?php

namespace App\Repository\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface LoginInterface
{
    /*
     * Login user
     * @param array $data
     * @return string
     */
    public function login(array $data): Model;
}
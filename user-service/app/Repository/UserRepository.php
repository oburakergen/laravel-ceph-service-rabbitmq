<?php

namespace App\Repository;

use App\Models\User;
use App\Repository\Contracts\CrudClass;
use App\Repository\Contracts\LoginInterface;

class UserRepository extends CrudClass implements LoginInterface
{
    public function __construct(protected User $user)
    {
        parent::__construct($this->user);
    }

    /**
     * @throws \Exception
     */
    public function login(array $data): User
    {
        try {
            return $this->user->where('email', $data['email'])->first();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
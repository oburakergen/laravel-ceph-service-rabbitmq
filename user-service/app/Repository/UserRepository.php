<?php

namespace App\Repository;

use App\Models\User;
use App\Repository\Contracts\CrudClass;
use App\Repository\Contracts\LoginInterface;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends CrudClass implements LoginInterface
{
    protected Model $model;
    public function __construct()
    {
        $this->model = new User();
        parent::__construct($this->model);
    }
    public function login(array $data): string
    {
        $user = $this->user->where('email', $data['email'])->first();
        if ($user) {
            if (password_verify($data['password'], $user->password)) {
                return $user->createToken('authToken')->plainTextToken;
            }
        }
        return '';
    }
}
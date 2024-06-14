<?php

namespace App\Service;

use App\Http\Resources\UserResource;
use App\Repository\UserRepository;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserService
{
    public function __construct(protected UserRepository $userRepo){}

    public function createUser(array $data): UserResource
    {
        return new UserResource($this->userRepo->create($data));
    }

    public function getAllUsers(): ResourceCollection
    {
        return UserResource::collection($this->userRepo->all());
    }

    public function updateUser(int $id,array $data): bool
    {
        return $this->userRepo->update($id, $data);
    }

    public function deleteUser($id): bool
    {
        return $this->userRepo->delete($id);
    }
}
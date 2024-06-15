<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Repository\UserRepository;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserService
{
    public function __construct(protected UserRepository $userRepo, protected RabbitMQService $rabbitMQService){}

    /**
     * @param array $data
     * @return UserResource
     * @throws \Exception
     */
    public function createUser(array $data): UserResource
    {
        $user = $this->userRepo->create($data);
        $this->rabbitMQService->sendMessage('horizon_queue', json_encode($user->toArray()));
        $this->rabbitMQService->sendMessage('license_queue', $user->id);

        return new UserResource($user);
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
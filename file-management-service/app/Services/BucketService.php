<?php

namespace App\Services;

use App\Repository\Contracts\BucketAction;
use App\Repository\UserBucketRepository;

class BucketService
{
    protected UserBucketRepository $userBucketRepository;
    public function __construct(protected RabbitMQService $rabbitMQService){
        $this->userBucketRepository = new UserBucketRepository();
    }

    public function createBucket(array $item): void
    {
        $this->userBucketRepository->create([
            'user_id' => $item['user_id'],
            'bucket_name' => 'bucket_' . $item['user_id'],
            'license_id' =>  $item['license_id']
        ]);

        $this->rabbitMQService->sendMessage('object_storage_queue', json_encode(
            [
                'action' => BucketAction::createBucket->name,
                'user_id' => $item['user_id'],
            ]
        ));
    }

    public function deleteBucket(array $item): void
    {
        $bucket = $this->userBucketRepository->findOne($item['user_id']);
        $this->rabbitMQService->sendMessage('object_storage_queue', json_encode(
            [
                'action' => BucketAction::deleteBucket->name,
                'bucket_name' => $bucket->bucket_name,
            ]
        ));
    }

    public function getBucketByUserId(int $userId)
    {
        return $this->bucketRepository->findOne($userId);
    }
}
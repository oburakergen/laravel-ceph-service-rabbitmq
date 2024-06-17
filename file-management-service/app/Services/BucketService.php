<?php

namespace App\Services;

use App\Repository\Contracts\BucketAction;
use App\Repository\UserBucketRepository;

class BucketService
{
    public function __construct(protected RabbitMQService $rabbitMQService, protected UserBucketRepository $bucketRepository){}

    public function createBucket(array $item): void
    {
//        $this->bucketRepository->create([
//            'user_id' => $item['user_id'],
//            'bucket_name' => 'bucket_' . $item['user_id'],
//            'license_id' =>  $item['license_id']
//        ]);
//
//        $this->rabbitMQService->sendMessage('object_storage_queue', json_encode(
//            [
//                'action' => BucketAction::createBucket->name,
//                'user_id' => $item['user_id'],
//            ]
//        ));
    }

    public function getBucketByUserId(int $userId)
    {
        return $this->bucketRepository->findOne($userId);
    }
}
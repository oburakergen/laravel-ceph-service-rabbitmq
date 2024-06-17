<?php

namespace App\Services;

use App\Http\Resources\UserLicenseResource;
use App\Repository\Contracts\BucketAction;
use App\Repository\LicenseRepository;
use Illuminate\Database\Eloquent\Model;

class UserLicenseService
{
    public function __construct(protected RabbitMQService $rabbitMQService, protected LicenseRepository $licenseRepository){}

    public function createLicense(int $userId): Model
    {
        $license = $this->licenseRepository->create(['user_id' => $userId]);

        $this->rabbitMQService->sendMessage('file_management_queue', json_encode([
            'action' => BucketAction::createUser->name,
            'user_id' => $userId,
            'license_id' => $license->id
        ]));

        return $license;
    }

    public function getLicenseByUserId(int $userId): UserLicenseResource
    {
        return new UserLicenseResource($this->licenseRepository->findOne($userId));
    }

    /**
     * @throws \Exception
     */
    public function updateLicense(array $data): bool
    {
        $license = $this->licenseRepository->findOne($data['user_id']);

        return $this->licenseRepository->update($license, $data);
    }
}
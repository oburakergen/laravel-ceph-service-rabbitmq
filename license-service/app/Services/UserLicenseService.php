<?php

namespace App\Services;

use App\Http\Resources\UserLicenseResource;
use App\Repository\Contracts\BucketAction;
use App\Repository\LicenseRepository;
use Illuminate\Database\Eloquent\Model;

class UserLicenseService
{
    protected LicenseRepository $licenseRepository;
    public function __construct(protected RabbitMQService $rabbitMQService){
        $this->licenseRepository = new LicenseRepository();
    }

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

    public function deleteLicense(int $userId): bool
    {
        $license = $this->licenseRepository->findOne($userId);

        $this->rabbitMQService->sendMessage('file_management_queue', json_encode([
            'action' => BucketAction::deletUser->name,
            'user_id' => $userId,
            'license_id' => $license->id
        ]));

        return $this->licenseRepository->delete($userId);
    }
}
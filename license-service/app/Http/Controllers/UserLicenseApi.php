<?php

namespace App\Http\Controllers;

use App\Services\UserLicenseService;
use Illuminate\Http\Request;

class UserLicenseApi extends Controller
{
    public function __construct(protected UserLicenseService $userLicenseService){}
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $userId = $request->user;

            return response()->success($this->userLicenseService->getLicenseByUserId($userId));
        } catch (\Exception $e) {
            return response()->error($e->getMessage());
        }
    }
}

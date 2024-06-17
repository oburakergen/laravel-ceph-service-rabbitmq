<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repository\Contracts\BucketAction;
use App\Services\AuthService;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(protected AuthService $authService){}

    public function login(LoginRequest $request)
    {
        try {
            return response()->success($this->authService->login($request->validated()));
        } catch (\Exception $e) {
            return response()->error($e->getMessage());
        }
    }
}

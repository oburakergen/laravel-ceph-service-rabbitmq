<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserApi extends Controller
{
    public function __construct(protected UserService $userService){
        $this->middleware('auth:api', ['except' => ['store']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json($this->userService->getAllUsers());
        } catch (\Exception $e) {
            return response()->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request): JsonResponse
    {
        try {
            return response()->success($this->userService->createUser($request->validated()));
        } catch (\Exception $e) {
            return response()->error($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        try {
            return response()->success($this->userService->updateUser(auth()->id(), $request->validated()));
        } catch (\Exception $e) {
            return response()->error($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): bool
    {
        try {
            return $this->userService->deleteUser(auth()->id());
        } catch (\Exception $e) {
            return response()->error($e->getMessage());
        }
    }
}

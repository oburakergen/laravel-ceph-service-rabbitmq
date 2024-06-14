<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Service\UserService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserApi extends Controller
{
    public function __construct(protected UserService $userService){
        $this->middleware('auth:api', ['except' => ['store', 'index']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection
    {
        return $this->userService->getAllUsers();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request): UserResource
    {

        return $this->userService->createUser($request->validated());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, int $id): bool
    {
        return $this->userService->updateUser($id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): bool
    {
        return $this->userService->deleteUser($id);
    }
}

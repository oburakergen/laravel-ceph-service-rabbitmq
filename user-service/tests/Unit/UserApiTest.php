<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;

class UserApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $userService;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = Mockery::mock(UserService::class);
        $this->app->instance(UserService::class, $this->userService);
    }

    public function testIndex()
    {
        $this->user = User::factory()->count(3)->make();

        $this->userService
            ->shouldReceive('getAllUsers')
            ->once()
            ->andReturn($this->user);

        $response = $this->getJson('/api/security/v1/users');

        $response->assertStatus(200)
            ->assertJson(['data' => $this->user->toArray()]);
    }

    public function testStore()
    {
        $userData = User::factory()->make()->toArray();

        $this->userService
            ->shouldReceive('createUser')
            ->once()
            ->with($userData)
            ->andReturn($userData);

        $response = $this->postJson('/api/security/v1/users', $userData);

        $response->assertStatus(200)
            ->assertJson(['data' => $userData]);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $updateData = User::factory()->make()->toArray();

        $this->userService
            ->shouldReceive('updateUser')
            ->once()
            ->with($user->id, $updateData)
            ->andReturn($updateData);

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson(['data' => $updateData]);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();

        $this->userService
            ->shouldReceive('deleteUser')
            ->once()
            ->with($user->id)
            ->andReturn(true);

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
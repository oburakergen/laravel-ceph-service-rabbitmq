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

        auth()->login(User::factory()->create());
    }

    public function testIndex()
    {
        $this->user = User::factory()->count(3)->make();

        $response = $this->get('/api/security/v1/users')->assertStatus(200);

        dd($response->json());
    }
//
//    public function testStore()
//    {
//        $userData = User::factory()->make()->toArray();
//
//        $this->userService
//            ->shouldReceive('createUser')
//            ->once()
//            ->with($userData)
//            ->andReturn($userData);
//
//        $response = $this->postJson('/api/security/v1/users', $userData);
//
//        $response->assertStatus(200)
//            ->assertJson(['data' => $userData]);
//    }
//
//    public function testUpdate()
//    {
//        $user = User::factory()->create();
//        $updateData = User::factory()->make()->toArray();
//
//        $this->userService
//            ->shouldReceive('updateUser')
//            ->once()
//            ->with($user->id, $updateData)
//            ->andReturn($updateData);
//
//        $response = $this->putJson("/api/security/v1/users/{$user->id}", $updateData);
//
//        $response->assertStatus(200)
//            ->assertJson(['data' => $updateData]);
//    }
//
//    public function testDestroy()
//    {
//        $user = User::factory()->create();
//
//        $this->userService
//            ->shouldReceive('deleteUser')
//            ->once()
//            ->with($user->id)
//            ->andReturn(true);
//
//        $response = $this->deleteJson("/api/security/v1/users/{$user->id}");
//
//        $response->assertStatus(200)
//            ->assertJson(['success' => true]);
//    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
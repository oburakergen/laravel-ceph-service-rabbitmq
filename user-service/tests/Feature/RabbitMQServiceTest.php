<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Services\RabbitMQService;
use App\Repository\UserRepository;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Mockery;

class RabbitMQServiceTest extends TestCase
{
    protected $rabbitMQService;
    protected $mockConnection;
    protected $mockChannel;
    protected $mockUserRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockConnection = Mockery::mock(AMQPStreamConnection::class);
        $this->mockChannel = Mockery::mock(AMQPChannel::class);
        $this->mockUserRepository = Mockery::mock(UserRepository::class);

        $this->mockConnection->shouldReceive('channel')
            ->andReturn($this->mockChannel);

        $this->mockChannel->shouldReceive('queue_declare')
            ->with(Mockery::type('string'), false, true, false, false)
            ->andReturnNull();

        $this->mockChannel->shouldReceive('close')
            ->andReturnNull();

        $this->mockConnection->shouldReceive('close')
            ->andReturnNull();

        $this->rabbitMQService = new RabbitMQService($this->mockConnection, $this->mockUserRepository);
    }

    public function testSendMessage()
    {
        $queue = 'user_queue';
        $message = 'test_message';

        $this->mockChannel->shouldReceive('basic_publish')
            ->once()
            ->with(Mockery::on(function ($msg) use ($message) {
                return $msg instanceof AMQPMessage && $msg->getBody() === $message;
            }), '', $queue);

        $this->rabbitMQService->sendMessage($queue, $message);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
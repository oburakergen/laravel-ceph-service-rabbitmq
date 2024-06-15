<?php

namespace App\Services;

use App\Repository\UserRepository;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQService
{
    protected AMQPStreamConnection $connection;
    protected AbstractChannel|AMQPChannel $channel;
    protected string $queue;

    public function __construct(AMQPStreamConnection $connection, protected UserRepository $userRepository)
    {
        $this->connection = $connection;
        $this->channel = $this->connection->channel();
        $this->queue = env('RABBITMQ_QUEUE', 'user_queue');
        $this->channel->queue_declare($this->queue, false, true, false, false);
    }

    public function sendMessage(string $queue, string $message): void
    {
        $this->channel->queue_declare($queue, false, true, false, false);
        $msg = new AMQPMessage($message, ['delivery_mode' => 2]);
        $this->channel->basic_publish($msg, '', $queue);
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
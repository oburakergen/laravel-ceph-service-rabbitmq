<?php

namespace App\Services;

use App\Repository\UserBucketRepository;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQService
{
    protected AMQPStreamConnection $connection;
    protected AbstractChannel|AMQPChannel $channel;
    protected string $queue;

    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
        $this->channel = $this->connection->channel();
        $this->queue = env('RABBITMQ_QUEUE', 'file_management_queue');
        $this->channel->queue_declare($this->queue, false, true, false, false);
    }

    /**
     * @throws \ErrorException
     */
    public function listenerQueue(): void
    {
        $callback = function (AMQPMessage $msg) {
            $message = json_decode($msg->body, true);

            $this->routeMessage($message['action'], $message);
            $msg->ack();
        };

        $this->channel->basic_consume($this->queue, '', false, true, false, false, $callback);
        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function sendMessage(string $queue, string $message): void
    {
        $this->channel->queue_declare($queue, false, true, false, false);
        $msg = new AMQPMessage($message, ['delivery_mode' => 2]);
        $this->channel->basic_publish($msg, '', $queue);
    }

    public function getMessage(string $queue, callable $callback): void
    {
        $this->channel->basic_consume($queue, '', false, true, false, false, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * @throws \Exception
     */
    public function routeMessage(string $function, array $payload): void
    {
//        $bucketService = new BucketService($this, $this->userBucketRepository);
//
//        switch ($function) {
//            case 'createUser':
//                $bucketService->createBucket($payload);
//                break;
//        }
    }
}
<?php

namespace App\Services;

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
        $this->queue = env('RABBITMQ_QUEUE', 'user_queue');
        $this->channel->queue_declare($this->queue, false, true, false, false);
    }

    /**
     * @throws \ErrorException
     */
    public function listenerQueue(): void
    {
        $callback = function (AMQPMessage $msg) {
            $data = json_decode($msg->body, true);

            $this->routeMessage($data['function'], $data);
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
        $bucketService = new MinioService();

        switch ($function) {
            case 'createUser':
                $bucketService->createBucket($payload['bucketName']);
                break;
            case 'createDelete':
                $bucketService->deleteObject($payload);
                break;
        }
    }
}
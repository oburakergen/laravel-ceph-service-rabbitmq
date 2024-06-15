<?php

namespace App\Services;

use App\Jobs\SendEmailJob;
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
        $this->queue = env('RABBITMQ_QUEUE', 'horizon_queue');
        $this->channel->queue_declare($this->queue, false, true, false, false);
    }

    /**
     * @throws \ErrorException
     */
    public function createUserMessages(): void
    {
        $callback = function (AMQPMessage $msg) {
            $data = json_decode($msg->body, true);

            SendEmailJob::dispatch($data);

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
}
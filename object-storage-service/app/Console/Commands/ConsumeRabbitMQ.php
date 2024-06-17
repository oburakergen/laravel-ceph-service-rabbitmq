<?php

namespace App\Console\Commands;

use App\Services\RabbitMQService;
use Illuminate\Console\Command;

class ConsumeRabbitMQ extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(protected RabbitMQService $rabbitMQService)
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     * @throws \ErrorException
     */
    public function handle(): void
    {
        $this->rabbitMQService->listenerQueue();
    }
}

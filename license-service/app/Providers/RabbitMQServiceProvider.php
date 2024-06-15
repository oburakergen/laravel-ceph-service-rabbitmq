<?php

namespace App\Providers;

use App\Repository\LicenseRepository;
use App\Services\RabbitMQService;
use Illuminate\Support\ServiceProvider;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(AMQPStreamConnection::class, function () {
            return new AMQPStreamConnection(
                env('RABBITMQ_HOST', 'rabbitmq'),
                env('RABBITMQ_PORT', 5672),
                env('RABBITMQ_USER', 'guest'),
                env('RABBITMQ_PASSWORD', 'guest')
            );
        });

        $this->app->singleton(RabbitMQService::class, function ($app) {
            return new RabbitMQService(
                $app->make(AMQPStreamConnection::class),
                $app->make(LicenseRepository::class)
            );
        });
    }
}

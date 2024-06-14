<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/security/v1')->group(function () {
    Route::apiResource('users', \App\Http\Controllers\UserApi::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('post.login');
});


Route::get('/send', function () {
    $connection = app(AMQPStreamConnection::class);
    $channel = $connection->channel();

    $channel->queue_declare('hello', false, false, false, false);

    $msg = new AMQPMessage('Hello World!');
    $channel->basic_publish($msg, '', 'hello');

    $channel->close();
    $connection->close();

    return 'Message sent to RabbitMQ!';
});
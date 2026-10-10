<?php

declare(strict_types=1);

use Appto\TelegramBot\Webhook\VerifyWebhookSecretMiddleware;
use Appto\TelegramBot\Webhook\WebhookController;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Route;

Route::any('/api/telegram/webhook/{botId}', WebhookController::class)
    ->name('api.telegram.webhook')
    ->middleware(['api', VerifyWebhookSecretMiddleware::class])
    ->withoutMiddleware(PreventRequestForgery::class);

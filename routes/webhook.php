<?php

declare(strict_types=1);

use Appto\TelegramBot\Webhook\VerifyWebhookSecretMiddleware;
use Appto\TelegramBot\Webhook\WebhookController;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Route;

Route::any('/telegram/webhook/{botId}', WebhookController::class)
    ->name('telegram.webhook')
    ->middleware(VerifyWebhookSecretMiddleware::class)
    ->withoutMiddleware(PreventRequestForgery::class);

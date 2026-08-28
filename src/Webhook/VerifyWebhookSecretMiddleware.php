<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Webhook;

use Appto\TelegramBot\Bot\BotManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class VerifyWebhookSecretMiddleware
{
    public function __construct(private BotManager $manager) {}

    public function handle(Request $request, \Closure $next): Response
    {
        $identity = $this->manager->findByName($request->route('botId'));
        $request->attributes->set('telegramBotIdentity', $identity);

        if (empty($identity->webhook_secret)) {
            return $next($request);
        }

        if ($request->header('X-Telegram-Bot-Api-Secret-Token') !== $identity->webhook_secret) {
            abort(Response::HTTP_UNAUTHORIZED, 'Invalid webhook secret');
        }

        return $next($request);
    }
}

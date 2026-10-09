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
        try {
            $identity = $this->manager->findByWebhookKey($request->route('botId'));
        } catch (\Exception $e) {
            abort(Response::HTTP_NOT_FOUND, $e->getMessage());
        }

        $request->attributes->set('telegramBotIdentity', $identity);

        if (empty($identity->webhook_secret)) {
            return $next($request);
        }

        abort_if(
            $request->header('X-Telegram-Bot-Api-Secret-Token') !== $identity->webhook_secret,
            code: Response::HTTP_UNAUTHORIZED,
            message: 'Invalid webhook secret'
        );

        return $next($request);
    }
}

<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Bot;

final readonly class DatabaseBotRepository implements BotRepository
{
    public function all(): array
    {
        return TelegramBotModel::query()
            ->where('is_active', true)
            ->get(['name', 'token', 'webhook_secret', 'handler_class'])
            ->map(fn ($bot) => BotIdentity::from([
                'name' => $bot->name,
                'token' => $bot->token,
                'webhook_secret' => $bot->webhook_secret,
                'handler' => $bot->handler_class,
            ]))
            ->toArray();
    }
}

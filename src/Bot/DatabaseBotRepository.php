<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Bot;

final readonly class DatabaseBotRepository implements BotRepository
{
    public function all(): array
    {
        return TelegramBotModel::query()
            ->where('is_active', true)
            ->get(['name', 'token', 'webhook_secret', 'handler'])
            ->mapWithKeys(fn ($bot) => [
                $bot->name => BotIdentity::from([
                    'id' => $bot->name,
                    'token' => $bot->token,
                    'webhook_secret' => $bot->webhook_secret,
                    'handler' => $bot->handler,
                ]),
            ])
            ->toArray();
    }
}

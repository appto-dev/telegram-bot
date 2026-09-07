<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Bot;

final readonly class DatabaseBotRepository implements BotRepository
{
    public function all(): array
    {
        $column = config('telegram-bot.database.webhook_key_column', 'name');

        return TelegramBotModel::query()
            ->where('is_active', true)
            ->get()
            ->mapWithKeys(function (TelegramBotModel $bot) use ($column) {
                $webhookKey = $bot->getAttribute($column);

                if ($webhookKey === null || $webhookKey === '') {
                    throw new \RuntimeException(
                        "Bot [{$bot->name}]: webhook key column [\"{$column}\"] configured via ".
                        'telegram-bot.database.webhook_key_column is empty on this row. Add the '.
                        'column via your own migration and backfill it for every active bot before using it.'
                    );
                }

                return [
                    $bot->name => BotIdentity::from([
                        'id' => $bot->name,
                        'token' => $bot->token,
                        'webhook_secret' => $bot->webhook_secret,
                        'handler' => $bot->handler,
                        'webhook_key' => (string) $webhookKey,
                    ]),
                ];
            })
            ->toArray();
    }
}

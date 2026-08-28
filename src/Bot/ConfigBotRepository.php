<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Bot;

final readonly class ConfigBotRepository implements BotRepository
{
    public function __construct(private array $bots) {}

    public function all(): array
    {
        $bots = [];

        foreach ($this->bots as $id => $bot) {
            $bots[$id] = BotIdentity::from([
                'id' => $id,
                'token' => $bot['token'],
                'webhook_secret' => $bot['webhook_secret'],
                'handler' => $bot['handler'],
            ]);
        }

        return $bots;
    }
}

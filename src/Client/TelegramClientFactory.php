<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client;

use Appto\TelegramBot\Bot\BotIdentity;

final readonly class TelegramClientFactory
{
    public function __construct(
        private array $httpConfig = [],
    ) {}

    public function make(BotIdentity $identity): TelegramClient
    {
        return new TelegramClient($identity, $this->httpConfig);
    }
}

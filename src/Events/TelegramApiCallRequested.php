<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Events;

use Appto\TelegramBot\Bot\BotIdentity;

final readonly class TelegramApiCallRequested
{
    public function __construct(
        public BotIdentity $bot,
        public string $method,
        public array $parameters,
    ) {}
}

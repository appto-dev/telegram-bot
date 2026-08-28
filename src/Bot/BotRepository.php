<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Bot;

interface BotRepository
{
    /** @return BotIdentity[] */
    public function all(): array;
}

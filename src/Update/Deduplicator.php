<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Update;

interface Deduplicator
{
    public function markAsProcessed(string $updateId, string $botId): bool;
}

<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Update;

use Illuminate\Support\Facades\Cache;

final class CacheDeduplicator implements Deduplicator
{
    public function markAsProcessed(string $updateId, string $botId): bool
    {
        return Cache::add("tg:update:{$botId}:{$updateId}", true, now()->addMinutes(10));
    }
}

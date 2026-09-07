<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Support;

final class SecretGenerator
{
    public static function generate(int $bytes = 24): string
    {
        return bin2hex(random_bytes($bytes));
    }
}

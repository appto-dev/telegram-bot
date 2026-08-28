<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Fixtures;

use Appto\TelegramBot\Update\UpdateContext;
use Appto\TelegramBot\Update\UpdateHandler;

/**
 * A handler that only records that it was invoked (and with which context), instead of
 * performing any real side effect — keeps router tests free of network/client concerns.
 */
final class RecordingHandler implements UpdateHandler
{
    public static ?UpdateContext $lastContext = null;

    public static int $calls = 0;

    public function handle(UpdateContext $context): void
    {
        self::$lastContext = $context;
        self::$calls++;
    }

    public static function reset(): void
    {
        self::$lastContext = null;
        self::$calls = 0;
    }
}

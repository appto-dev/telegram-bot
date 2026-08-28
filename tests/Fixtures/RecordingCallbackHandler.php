<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Fixtures;

use Appto\TelegramBot\Contracts\CallbackHandler;
use Appto\TelegramBot\Update\UpdateContext;

/**
 * Records that it was invoked and with which params, without performing any real side effect —
 * keeps CallbackRouter tests free of network/client concerns.
 */
final class RecordingCallbackHandler implements CallbackHandler
{
    public static ?array $lastParams = null;

    public static int $calls = 0;

    public function handle(UpdateContext $context, array $params): void
    {
        self::$lastParams = $params;
        self::$calls++;
    }

    public static function reset(): void
    {
        self::$lastParams = null;
        self::$calls = 0;
    }
}

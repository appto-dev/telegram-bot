<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Fixtures;

use Appto\TelegramBot\Contracts\RequiresPermission;
use Appto\TelegramBot\Update\UpdateContext;
use Appto\TelegramBot\Update\UpdateHandler;

/**
 * A handler that always denies authorization, so router tests can assert a matched route
 * still returns true (route found) even when the handler itself refuses to run.
 */
final class DeniedHandler implements RequiresPermission, UpdateHandler
{
    public static int $handleCalls = 0;

    public function authorize(UpdateContext $context): bool
    {
        return false;
    }

    public function handle(UpdateContext $context): void
    {
        self::$handleCalls++;
    }

    public static function reset(): void
    {
        self::$handleCalls = 0;
    }
}

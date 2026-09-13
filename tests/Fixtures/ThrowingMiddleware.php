<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Fixtures;

use Appto\TelegramBot\Exceptions\UserThrottleExceededException;
use Appto\TelegramBot\Update\UpdateContext;
use Appto\TelegramBot\Update\UpdateMiddleware;
use Closure;

/**
 * Always throws, unconditionally — used to test Bot::dispatch()'s catch/onThrottled() hook
 * without needing to actually exhaust a rate limiter first.
 */
final class ThrowingMiddleware implements UpdateMiddleware
{
    public function handle(UpdateContext $context, Closure $next): void
    {
        throw new UserThrottleExceededException($context, 5);
    }
}

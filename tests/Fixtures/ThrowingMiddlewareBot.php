<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Fixtures;

use Appto\TelegramBot\Bot\Bot;

/**
 * Registers a middleware that always throws a throttle exception — exercises Bot::dispatch()'s
 * catch/report() behavior without needing to actually exhaust a rate limiter first.
 */
final class ThrowingMiddlewareBot extends Bot
{
    protected function boot(): void
    {
        $this->middleware([ThrowingMiddleware::class]);
    }
}

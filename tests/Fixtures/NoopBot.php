<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Fixtures;

use Appto\TelegramBot\Bot\Bot;

/**
 * A bot with no routes and no middleware — for tests that only care about Bot::dispatch()'s
 * own behaviour (e.g. events fired before routing), not any handler being invoked.
 */
final class NoopBot extends Bot
{
    protected function boot(): void {}
}

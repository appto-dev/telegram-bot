<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Update;

use Appto\TelegramBot\Exceptions\ThrottleExceededException;
use Appto\TelegramBot\Exceptions\UserThrottleExceededException;

/**
 * Limits how many updates a single Telegram user may send within a time window, regardless
 * of which chat they send them from. Falls back to the chat id when there is no "from" user
 * (e.g. channel posts).
 */
final class ThrottleMiddleware extends BaseThrottleMiddleware
{
    protected function identifier(UpdateContext $context): int|string|null
    {
        return $context->userId() ?? $context->chatId();
    }

    protected function keyPrefix(): string
    {
        return 'user';
    }

    protected function exception(UpdateContext $context, int $retryAfter): ThrottleExceededException
    {
        return new UserThrottleExceededException($context, $retryAfter);
    }
}

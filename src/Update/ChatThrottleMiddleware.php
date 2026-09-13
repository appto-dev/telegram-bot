<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Update;

use Appto\TelegramBot\Exceptions\ChatThrottleExceededException;
use Appto\TelegramBot\Exceptions\ThrottleExceededException;

/**
 * Limits how many updates a single chat may produce within a time window, regardless of which
 * user sends them — protects group/supergroup chats from being flooded by many different users
 * even when each of them is individually under the ThrottleMiddleware limit.
 */
final class ChatThrottleMiddleware extends BaseThrottleMiddleware
{
    protected function identifier(UpdateContext $context): int|string|null
    {
        return $context->chatId();
    }

    protected function keyPrefix(): string
    {
        return 'chat';
    }

    protected function exception(UpdateContext $context, int $retryAfter): ThrottleExceededException
    {
        return new ChatThrottleExceededException($context, $retryAfter);
    }
}

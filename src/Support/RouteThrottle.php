<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Support;

use Appto\TelegramBot\Exceptions\ChatThrottleExceededException;
use Appto\TelegramBot\Exceptions\UserThrottleExceededException;
use Appto\TelegramBot\Update\UpdateContext;
use Illuminate\Support\Facades\RateLimiter;

final class RouteThrottle
{
    /**
     * @param  'user'|'chat'  $scope
     */
    public static function hit(
        UpdateContext $context,
        string $routerKey,
        string $pattern,
        string $scope,
        int $maxAttempts,
        int $decaySeconds,
    ): void {
        $identifier = $scope === 'chat'
            ? $context->chatId()
            : ($context->userId() ?? $context->chatId());

        if ($identifier === null) {
            return;
        }

        $key = sprintf(
            'tg:throttle:%s:route:%s:%s:%s:%s',
            $context->bot->id,
            $routerKey,
            $scope,
            sha1($pattern),
            $identifier,
        );

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);

            throw $scope === 'chat'
                ? new ChatThrottleExceededException($context, $retryAfter)
                : new UserThrottleExceededException($context, $retryAfter);
        }

        RateLimiter::hit($key, $decaySeconds);
    }
}

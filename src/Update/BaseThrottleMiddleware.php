<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Update;

use Appto\TelegramBot\Exceptions\ThrottleExceededException;
use Closure;
use Illuminate\Support\Facades\RateLimiter;

abstract class BaseThrottleMiddleware implements UpdateMiddleware
{
    public function handle(
        UpdateContext $context,
        Closure $next,
        ?string $maxAttempts = null,
        ?string $decaySeconds = null,
    ): void {
        $identifier = $this->identifier($context);

        if ($identifier === null) {
            $next($context);

            return;
        }

        $key = "tg:throttle:{$context->bot->id}:{$this->keyPrefix()}:{$identifier}";
        $maxAttempts = (int) ($maxAttempts ?? 20);
        $decaySeconds = (int) ($decaySeconds ?? 60);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            throw $this->exception($context, RateLimiter::availableIn($key));
        }

        RateLimiter::hit($key, $decaySeconds);
        $next($context);
    }

    abstract protected function identifier(UpdateContext $context): int|string|null;

    abstract protected function keyPrefix(): string;

    abstract protected function exception(UpdateContext $context, int $retryAfter): ThrottleExceededException;
}

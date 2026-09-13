<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Exceptions;

use Appto\TelegramBot\Update\UpdateContext;

abstract class ThrottleExceededException extends \RuntimeException
{
    public function __construct(
        public readonly UpdateContext $context,
        public readonly int $retryAfter,
    ) {
        parent::__construct(static::class." (retry after {$retryAfter}s)");
    }
}

<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Update;

use Closure;

interface UpdateMiddleware
{
    /**
     * @param  Closure(UpdateContext): void  $next
     */
    public function handle(UpdateContext $context, Closure $next): void;
}

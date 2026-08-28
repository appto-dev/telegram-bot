<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Support;

use Appto\TelegramBot\Contracts\RequiresPermission;
use Appto\TelegramBot\Update\UpdateContext;
use Appto\TelegramBot\Update\UpdateHandler;

final class HandlerInvoker
{
    /**
     * @param  class-string|callable  $handler
     * @param  array<string, mixed>  $parameters
     */
    public static function call(string|callable $handler, array $parameters): void
    {
        $context = $parameters['context'] ?? null;
        if (! $context instanceof UpdateContext) {
            throw new \InvalidArgumentException('Context must be an instance of UpdateContext.');
        }

        if (! is_string($handler)) {
            app()->call($handler, $parameters);

            return;
        }

        /** @var UpdateHandler $instance */
        $instance = app($handler);

        if ($instance instanceof RequiresPermission && ! $instance->authorize($context)) {
            UnauthorizedResponder::respond($context, $instance);

            return;
        }

        app()->call([$instance, 'handle'], $parameters);
    }
}

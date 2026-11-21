<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Routing;

use Appto\TelegramBot\Support\HandlerInvoker;
use Appto\TelegramBot\Support\RouteThrottle;
use Appto\TelegramBot\Update\UpdateContext;

final class CommandRouter implements RouterContract
{
    /** @var array<string, RouteDefinition> */
    private array $commands = [];

    public static function key(): string
    {
        return 'command';
    }

    public function add(string $pattern, string|callable $handler): RouteDefinition
    {
        return $this->commands[$pattern] = new RouteDefinition($pattern, $handler);
    }

    public function dispatch(UpdateContext $context): bool
    {
        $name = $context->command();
        if (! $name || ! isset($this->commands[$name])) {
            return false;
        }

        $route = $this->commands[$name];

        if ($throttle = $route->throttleConfig()) {
            RouteThrottle::hit($context, self::key(), $route->pattern(), $throttle['scope'], $throttle['max'], $throttle['decay']);
        }

        HandlerInvoker::call($route->handler(), ['context' => $context]);

        return true;
    }

    /** @return array<string, RouteDefinition> */
    public function all(): array
    {
        return $this->commands;
    }
}

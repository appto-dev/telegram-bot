<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Update;

use Appto\TelegramBot\Routing\RouteDefinition;
use Appto\TelegramBot\Routing\RouterContract;
use Appto\TelegramBot\Support\HandlerInvoker;
use Appto\TelegramBot\Support\RouteThrottle;

final class UpdateRouter implements RouterContract
{
    /** @var array<string, RouteDefinition> */
    private array $routes = [];

    public static function key(): string
    {
        return 'update';
    }

    public function add(string $pattern, string|callable $handler): RouteDefinition
    {
        return $this->routes[$pattern] = new RouteDefinition($pattern, $handler);
    }

    public function dispatch(UpdateContext $context): bool
    {
        if (! count($this->routes)) {
            return false;
        }

        $updateType = UpdateType::detect($context->update());
        $route = $this->routes[$updateType->value] ?? null;

        if ($route) {
            if ($throttle = $route->throttleConfig()) {
                RouteThrottle::hit($context, self::key(), $route->pattern(), $throttle['scope'], $throttle['max'], $throttle['decay']);
            }

            HandlerInvoker::call($route->handler(), ['context' => $context]);

            return true;
        }

        return false;
    }

    /** @return array<string, RouteDefinition> */
    public function all(): array
    {
        return $this->routes;
    }
}

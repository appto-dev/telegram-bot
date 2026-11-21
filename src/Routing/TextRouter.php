<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Routing;

use Appto\TelegramBot\Support\HandlerInvoker;
use Appto\TelegramBot\Support\RouteThrottle;
use Appto\TelegramBot\Update\UpdateContext;

final class TextRouter implements RouterContract
{
    /** @var array<string, RouteDefinition> */
    private array $routes = [];

    public static function key(): string
    {
        return 'text';
    }

    public function add(string $pattern, string|callable $handler): RouteDefinition
    {
        $pattern = $this->normalize($pattern);

        return $this->routes[$pattern] = new RouteDefinition($pattern, $handler);
    }

    public function dispatch(UpdateContext $context): bool
    {
        $message = $context->message();
        $text = $message?->text ?? $message?->caption;

        if (! $text) {
            return false;
        }

        $route = $this->routes[$this->normalize($text)] ?? null;

        if ($route) {
            if ($throttle = $route->throttleConfig()) {
                RouteThrottle::hit($context, self::key(), $route->pattern(), $throttle['scope'], $throttle['max'], $throttle['decay']);
            }

            HandlerInvoker::call($route->handler(), ['context' => $context]);

            return true;
        }

        return false;
    }

    private function normalize(string $text): string
    {
        return mb_strtolower(trim($text));
    }

    /** @return array<string, RouteDefinition> */
    public function all(): array
    {
        return $this->routes;
    }
}

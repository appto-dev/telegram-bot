<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Routing;

use Appto\TelegramBot\Exceptions\RouterException;
use Appto\TelegramBot\Update\UpdateContext;

final class RouterRegistry
{
    private array $routes = [];

    private array $resolved = [];

    /**
     * @param  class-string<RouterContract>|array<string, class-string<RouterContract>>  $router
     *
     * @throws RouterException
     */
    public function register(string|array|null $router = null): void
    {
        if (is_array($router)) {
            foreach ($router as $instance) {
                $this->register($instance);
            }

            return;
        }

        $this->routes[$router::key()] = $router;
    }

    /**
     * @throws RouterException
     */
    public function dispatch(UpdateContext $context): bool
    {
        foreach ($this->routes as $name => $router) {
            if ($this->get($name)->dispatch($context)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws RouterException
     */
    public function get(string $name): RouterContract
    {
        if (isset($this->resolved[$name])) {
            return $this->resolved[$name];
        }

        $class = $this->routes[$name]
            ?? throw new RouterException("Router [{$name}] is not registered.");

        return $this->resolved[$name] = app($class);
    }
}

<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Update;

use Appto\TelegramBot\Routing\RouterContract;
use Appto\TelegramBot\Support\HandlerInvoker;

final class UpdateRouter implements RouterContract
{
    private array $routes = [];

    public static function key(): string
    {
        return 'update';
    }

    public function add(string $pattern, string|callable $handler): void
    {
        $this->routes[$pattern] = $handler;
    }

    public function dispatch(UpdateContext $context): bool
    {
        if (! count($this->routes)) {
            return false;
        }

        $updateType = UpdateType::detect($context->update());
        $handler = $this->routes[$updateType->value] ?? null;

        if ($handler) {
            HandlerInvoker::call($handler, ['context' => $context]);

            return true;
        }

        return false;
    }

    public function all(): array
    {
        return $this->routes;
    }
}

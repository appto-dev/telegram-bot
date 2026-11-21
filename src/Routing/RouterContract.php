<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Routing;

use Appto\TelegramBot\Update\UpdateContext;

interface RouterContract
{
    public static function key(): string;

    public function add(string $pattern, string|callable $handler): RouteDefinition;

    public function dispatch(UpdateContext $context): bool;

    /** @return array<string, RouteDefinition> */
    public function all(): array;
}

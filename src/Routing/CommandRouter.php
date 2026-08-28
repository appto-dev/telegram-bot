<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Routing;

use Appto\TelegramBot\Contracts\CommandHandler;
use Appto\TelegramBot\Support\HandlerInvoker;
use Appto\TelegramBot\Update\UpdateContext;

final class CommandRouter implements RouterContract
{
    /** @var array<string, class-string<CommandHandler>> */
    private array $commands = [];

    public static function key(): string
    {
        return 'command';
    }

    /**
     * @param  class-string<CommandHandler>|callable  $handler
     */
    public function add(string $pattern, string|callable $handler): void
    {
        $this->commands[$pattern] = $handler;
    }

    public function dispatch(UpdateContext $context): bool
    {
        $name = $context->command();
        if (! $name || ! isset($this->commands[$name])) {
            return false;
        }

        HandlerInvoker::call($this->commands[$name], ['context' => $context]);

        return true;
    }

    public function all(): array
    {
        return $this->commands;
    }
}

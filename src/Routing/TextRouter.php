<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Routing;

use Appto\TelegramBot\Support\HandlerInvoker;
use Appto\TelegramBot\Update\UpdateContext;

final class TextRouter implements RouterContract
{
    private array $routes = [];

    public static function key(): string
    {
        return 'text';
    }

    public function add(string $pattern, string|callable $handler): void
    {
        $this->routes[$this->normalize($pattern)] = $handler;
    }

    public function dispatch(UpdateContext $context): bool
    {
        $message = $context->message();
        $text = $message?->text ?? $message?->caption;

        if (! $text) {
            return false;
        }

        $handler = $this->routes[$this->normalize($text)] ?? null;

        if ($handler) {
            HandlerInvoker::call($handler, ['context' => $context]);

            return true;
        }

        return false;
    }

    private function normalize(string $text): string
    {
        return mb_strtolower(trim($text));
    }

    public function all(): array
    {
        return $this->routes;
    }
}

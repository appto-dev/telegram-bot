<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Routing;

use Appto\TelegramBot\Support\HandlerInvoker;
use Appto\TelegramBot\Update\UpdateContext;

final class CallbackRouter implements RouterContract
{
    private array $routes = [];

    public static function key(): string
    {
        return 'callback';
    }

    public function add(string $pattern, string|callable $handler): void
    {
        $this->routes[$pattern] = $handler;
    }

    public function dispatch(UpdateContext $context): bool
    {
        if (! $context->update()->callback_query) {
            return false;
        }

        $data = $context->update()->callback_query->data;

        foreach ($this->routes as $pattern => $handlerClass) {
            $params = $this->match($pattern, $data);

            if (! is_null($params)) {
                HandlerInvoker::call($handlerClass, ['context' => $context, 'params' => $params]);

                return true;
            }
        }

        return false;
    }

    public function all(): array
    {
        return $this->routes;
    }

    /**
     * order:confirm {id} => ['id' => '1']
     * order:confirm {id?} => ['id' => '1'] or ['id' => null] if omitted
     *
     * Named groups are plain {name}/{name?} placeholders — no type/length
     * annotations are supported (e.g. {amount:string} is treated as a literal).
     */
    private function match(string $pattern, string $data): ?array
    {
        $tokens = preg_split('/(\{\w+\??})/', $pattern, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $regex = '';
        $literal = '';

        foreach ($tokens as $token) {
            if (! preg_match('/^\{(\w+)(\?)?}$/', $token, $m)) {
                $literal .= $token;

                continue;
            }

            [$name, $optional] = [$m[1], ($m[2] ?? '') === '?'];

            if ($optional && $literal !== '') {
                $separator = mb_substr($literal, -1);
                $prefix = mb_substr($literal, 0, -1);

                $regex .= preg_quote($prefix, '/');
                $regex .= '(?:'.preg_quote($separator, '/').'(?P<'.$name.'>[^'.preg_quote($separator, '/').']+))?';
            } else {
                $regex .= preg_quote($literal, '/');
                $regex .= '(?P<'.$name.'>.+?)';
            }

            $literal = '';
        }

        $regex .= preg_quote($literal, '/');

        if (! preg_match('/^'.$regex.'$/', $data, $matches, PREG_UNMATCHED_AS_NULL)) {
            return null;
        }

        return array_filter($matches, static fn ($key) => is_string($key), ARRAY_FILTER_USE_KEY);
    }
}

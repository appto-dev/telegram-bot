<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Routing;

use Appto\TelegramBot\Bot\BotManager;
use Appto\TelegramBot\Contracts\CommandHandler;
use Appto\TelegramBot\Contracts\HasDescription;
use Appto\TelegramBot\Contracts\RequiresPermission;
use Appto\TelegramBot\Update\UpdateContext;

final readonly class HelpCommand implements CommandHandler, HasDescription
{
    public function __construct(private BotManager $bots) {}

    public static function description(): string
    {
        return __('telegram-bot::help.command_description');
    }

    public function handle(UpdateContext $context): void
    {
        $bot = $this->bots->resolve($context->bot);

        $lines = collect($bot->router()->get(CommandRouter::key())->all())
            ->filter(fn (RouteDefinition $route) => is_string($route->handler()) && is_subclass_of($route->handler(), HasDescription::class))
            ->filter(fn (RouteDefinition $route) => $this->authorized($route->handler(), $context))
            ->map(fn (RouteDefinition $route, string $name) => sprintf(
                '/%s — %s',
                $name,
                htmlspecialchars($route->handler()::description(), ENT_QUOTES),
            ))
            ->sort()
            ->values();

        if ($lines->isEmpty()) {
            $context->reply(__('telegram-bot::help.empty'));

            return;
        }

        $context->reply(
            '<b>'.__('telegram-bot::help.title').'</b>'."\n\n".$lines->join("\n"),
            parse_mode: 'HTML',
        );
    }

    /**
     * @param  class-string  $class
     */
    private function authorized(string $class, UpdateContext $context): bool
    {
        if (! is_subclass_of($class, RequiresPermission::class)) {
            return true;
        }

        return app($class)->authorize($context);
    }
}

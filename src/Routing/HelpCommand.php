<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Routing;

use Appto\TelegramBot\Bot\BotManager;
use Appto\TelegramBot\Contracts\CommandHandler;
use Appto\TelegramBot\Contracts\HasDescription;
use Appto\TelegramBot\Contracts\RequiresPermission;
use Appto\TelegramBot\Update\UpdateContext;

final readonly class HelpCommand implements CommandHandler
{
    public function __construct(private BotManager $bots) {}

    public function handle(UpdateContext $context): void
    {
        $bot = $this->bots->resolve($context->bot);

        /** @var class-string<HasDescription> $class */
        $lines = collect($bot->router()->get(CommandRouter::key())->all())
            ->filter(fn (string|callable $class) => is_string($class) && is_subclass_of($class, HasDescription::class))
            ->filter(fn (string $class) => $this->authorized($class, $context))
            ->map(fn (string $class, string $name) => sprintf(
                '/%s — %s',
                $name,
                htmlspecialchars($class::description(), ENT_QUOTES),
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

    private function authorized(string $class, UpdateContext $context): bool
    {
        if (! is_subclass_of($class, RequiresPermission::class)) {
            return true;
        }

        return app($class)->authorize($context);
    }
}

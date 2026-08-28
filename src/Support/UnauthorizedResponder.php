<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Support;

use Appto\TelegramBot\Contracts\HasUnauthorizedMessage;
use Appto\TelegramBot\Update\UpdateContext;

final class UnauthorizedResponder
{
    public static function respond(UpdateContext $context, object $handler): void
    {
        $message = $handler instanceof HasUnauthorizedMessage
            ? $handler->unauthorizedMessage($context)
            : self::defaultMessage();

        if ($context->isCallbackQuery()) {
            $context->answerCallbackQuery($message, config('telegram-bot.unauthorized.show_alert', true));

            return;
        }

        if ($message !== null) {
            $context->reply($message);
        }
    }

    private static function defaultMessage(): ?string
    {
        $message = config('telegram-bot.unauthorized.message');

        return $message !== null ? __($message) : null;
    }
}

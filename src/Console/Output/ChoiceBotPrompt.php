<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Console\Output;

use Appto\TelegramBot\Bot\BotIdentity;
use Appto\TelegramBot\Bot\BotManager;

use function Laravel\Prompts\select;

final class ChoiceBotPrompt
{
    public static function handle(BotManager $botManager): string
    {
        $bots = array_map(fn (BotIdentity $bot) => $bot->id.' <fg=gray>('.$bot->handler.')</>', $botManager->all());

        return select(
            label: 'Choose a bot',
            options: $bots,
            hint: 'Choose a bot from your repository (source: '.config('telegram-bot.repository').')',
        );
    }
}

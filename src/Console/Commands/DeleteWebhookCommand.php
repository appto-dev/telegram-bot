<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Console\Commands;

use Appto\TelegramBot\Bot\BotManager;
use Appto\TelegramBot\Client\TelegramClient;
use Appto\TelegramBot\Client\TelegramClientFactory;
use Appto\TelegramBot\Console\Output\ChoiceBotPrompt;
use Appto\TelegramBot\Console\Output\WebhookPrompts;
use Illuminate\Console\Command;

final class DeleteWebhookCommand extends Command
{
    protected $signature = 'telegram:delete-webhook {bot?} {--drop : Drop all pending updates}';

    protected $description = 'Deletes the bot\'s webhook, switching it back to long polling';

    /**
     * Execute the console command.
     */
    public function handle(BotManager $manager): void
    {
        $bot = $this->argument('bot') ?? ChoiceBotPrompt::handle($manager);
        $identity = $manager->findByName($bot);

        /** @var TelegramClient $client */
        $client = app(TelegramClientFactory::class)->make($identity);
        $prompt = new WebhookPrompts($client);

        $prompt->remove();
    }
}

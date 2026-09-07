<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Console\Commands;

use Appto\TelegramBot\Support\SecretGenerator;
use Illuminate\Console\Command;

class GenerateWebhookSecret extends Command
{
    protected $signature = 'telegram:webhook-secret';

    protected $description = 'Generates a random string for TELEGRAM_WEBHOOK_SECRET';

    public function handle(): int
    {
        $secret = SecretGenerator::generate();

        $this->components->info('Copy this secret into TELEGRAM_WEBHOOK_SECRET in your .env file:');
        \Laravel\Prompts\info($secret);
        $this->components->warn('Keep it private — do not commit or share this value.');

        return self::SUCCESS;
    }
}

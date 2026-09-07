<?php

namespace Appto\TelegramBot\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateWebhookSecret extends Command
{
    protected $signature = 'telegram:webhook-secret {--length=40 : String length}';

    protected $description = 'Generates a random string for TELEGRAM_WEBHOOK_SECRET';

    public function handle(): int
    {
        $secret = Str::random((int) $this->option('length'));

        $this->components->info('Copy this secret into TELEGRAM_WEBHOOK_SECRET in your .env file:');
        \Laravel\Prompts\info($secret);
        $this->components->warn('Keep it private — do not commit or share this value.');

        return self::SUCCESS;
    }
}

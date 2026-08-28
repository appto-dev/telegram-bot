<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Console\Commands;

use Appto\TelegramBot\Bot\TelegramBotModel;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('telegram:migrate-bots-to-database
    {--force : Overwrite bots that already exist in the database}
    {--dry-run : Show what would happen without writing anything}')]
#[Description('Copies bots from the config file ("telegram-bot.bots") into the telegram_bots table')]
final class MigrateBotsToDatabaseCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $bots = config('telegram-bot.bots', []);

        if ($bots === []) {
            $this->components->warn('No bots found in config("telegram-bot.bots").');

            return self::SUCCESS;
        }

        $force = (bool) $this->option('force');
        $dryRun = (bool) $this->option('dry-run');

        $rows = [];

        foreach ($bots as $name => $bot) {
            $existing = TelegramBotModel::query()->where('name', $name)->first();

            if ($existing && ! $force) {
                $rows[] = [$name, $bot['bot'], 'skipped (already exists, use --force to overwrite)'];

                continue;
            }

            $action = $existing ? 'updated' : 'created';

            if ($dryRun) {
                $rows[] = [$name, $bot['bot'], "would be {$action} (dry run)"];

                continue;
            }

            $model = $existing ?? new TelegramBotModel;
            $model->name = $name;
            $model->token = $bot['token'];
            $model->handler = $bot['bot'];
            $model->is_active = true;

            if (! empty($bot['webhook_secret'])) {
                $model->webhook_secret = $bot['webhook_secret'];
            }

            $model->save();

            $rows[] = [$name, $bot['bot'], $action];
        }

        $this->table(['Name', 'Handler', 'Result'], $rows);

        if (! $dryRun) {
            $this->components->info('Set TELEGRAM_BOT_REPOSITORY=database in .env to start reading bots from the database.');
        }

        return self::SUCCESS;
    }
}

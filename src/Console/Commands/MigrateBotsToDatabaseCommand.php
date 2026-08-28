<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Console\Commands;

use Appto\TelegramBot\Bot\TelegramBotModel;
use Illuminate\Console\Command;

final class MigrateBotsToDatabaseCommand extends Command
{
    protected $signature = 'telegram:migrate-bots-db
        {--force : Overwrite bots that already exist in the database}
        {--dry-run : Show what would happen without writing anything}';

    protected $description = 'Copies bots from the config file ("telegram-bot.bots") into the telegram_bots table';

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
                $rows[] = [$name, $bot['handler'], 'skipped (already exists, use --force to overwrite)'];

                continue;
            }

            $action = $existing ? 'updated' : 'created';

            if ($dryRun) {
                $rows[] = [$name, $bot['handler'], "would be {$action} (dry run)"];

                continue;
            }

            $model = $existing ?? new TelegramBotModel;

            $model->fill([
                'name' => $name,
                'token' => $bot['token'],
                'handler' => $bot['handler'],
                'is_active' => true,
            ]);

            if (! empty($bot['webhook_secret'])) {
                $model->fill(['webhook_secret' => $bot['webhook_secret']]);
            }

            $model->save();

            $rows[] = [$name, $bot['handler'], $action];
        }

        $this->table(['Name', 'Handler', 'Result'], $rows);

        if (! $dryRun) {
            $this->components->info('Set TELEGRAM_BOT_REPOSITORY=database in .env to start reading bots from the database.');
        }

        return self::SUCCESS;
    }
}

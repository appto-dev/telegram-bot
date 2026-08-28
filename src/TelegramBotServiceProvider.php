<?php

declare(strict_types=1);

namespace Appto\TelegramBot;

use Appto\TelegramBot\Bot\BotManager;
use Appto\TelegramBot\Bot\BotRepository;
use Appto\TelegramBot\Bot\ConfigBotRepository;
use Appto\TelegramBot\Bot\DatabaseBotRepository;
use Appto\TelegramBot\Client\TelegramClientFactory;
use Appto\TelegramBot\Console\Commands\DeleteWebhookCommand;
use Appto\TelegramBot\Console\Commands\PollCommand;
use Appto\TelegramBot\Console\Commands\RoutesListCommand;
use Appto\TelegramBot\Console\Commands\SetWebhookCommand;
use Appto\TelegramBot\Dialog\DialogManager;
use Appto\TelegramBot\Dialog\DialogStateRepository;
use Appto\TelegramBot\Dialog\EloquentDialogStateRepository;
use Appto\TelegramBot\Update\CacheDeduplicator;
use Appto\TelegramBot\Update\Deduplicator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class TelegramBotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/telegram-bot.php', 'telegram-bot');

        $this->app->singleton(Deduplicator::class, CacheDeduplicator::class);

        $this->app->singleton(BotRepository::class, fn (Application $app) => match ($app['config']['telegram-bot']['repository']) {
            'config' => new ConfigBotRepository($app['config']['telegram-bot']['bots']),
            'database' => $app->make(DatabaseBotRepository::class),
            default => throw new \InvalidArgumentException('Invalid repository'),
        });

        $this->app->singleton(
            BotManager::class,
            fn (Application $app) => new BotManager($app, $app[BotRepository::class])
        );

        $this->app->singleton(
            TelegramClientFactory::class,
            fn (Application $app) => new TelegramClientFactory($this->app['config']['telegram-bot']['http'])
        );

        $this->app->singleton(DialogStateRepository::class, EloquentDialogStateRepository::class);

        $this->app->singleton(DialogManager::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/webhook.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'telegram-bot');

        if ($this->app->runningInConsole()) {
            $this->commands([
                PollCommand::class,
                SetWebhookCommand::class,
                DeleteWebhookCommand::class,
                RoutesListCommand::class,

            ]);

            $this->publishes([
                __DIR__.'/../config/telegram-bot.php' => config_path('telegram-bot.php'),
            ], 'telegram-bot-config');

            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'telegram-bot-migrations');

            $this->publishes([
                __DIR__.'/../resources/lang' => $this->app->langPath('vendor/telegram-bot'),
            ], 'telegram-bot-lang');
        }
    }
}

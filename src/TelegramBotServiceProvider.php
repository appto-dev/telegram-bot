<?php

declare(strict_types=1);

namespace Appto\TelegramBot;

use Appto\TelegramBot\Bot\BotManager;
use Appto\TelegramBot\Bot\BotRepository;
use Appto\TelegramBot\Bot\ConfigBotRepository;
use Appto\TelegramBot\Bot\DatabaseBotRepository;
use Appto\TelegramBot\Client\TelegramClientFactory;
use Appto\TelegramBot\Console\Commands\DeleteWebhookCommand;
use Appto\TelegramBot\Console\Commands\GenerateWebhookSecret;
use Appto\TelegramBot\Console\Commands\MigrateBotsToDatabaseCommand;
use Appto\TelegramBot\Console\Commands\PollCommand;
use Appto\TelegramBot\Console\Commands\RoutesListCommand;
use Appto\TelegramBot\Console\Commands\SetWebhookCommand;
use Appto\TelegramBot\Dialog\DialogManager;
use Appto\TelegramBot\Dialog\DialogStateRepository;
use Appto\TelegramBot\Dialog\EloquentDialogStateRepository;
use Appto\TelegramBot\Exceptions\ThrottleExceededException;
use Appto\TelegramBot\Update\CacheDeduplicator;
use Appto\TelegramBot\Update\Deduplicator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Exceptions\Handler;
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

        // Silent by default: Bot::dispatch() already caught this and dropped the update — reporting
        // it too would just be log noise. An app that wants to react (reply, alert, ...) opts back in
        // via bootstrap/app.php: $exceptions->stopIgnoring(ThrottleExceededException::class) plus its
        // own ->reportable(...), same convention Laravel itself uses for HttpException et al.
        // Hooked on the concrete Handler rather than the contract: in console, Collision resolves the
        // handler during register() and rebinds the contract to its own wrapper without dontReport().
        // `resolving` (not afterResolving) so it runs before bootstrap/app.php's withExceptions()
        // callback, letting the app's stopIgnoring() win.
        $this->app->resolving(Handler::class, function (Handler $handler): void {
            $handler->dontReport(ThrottleExceededException::class);
        });
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
                MigrateBotsToDatabaseCommand::class,
                GenerateWebhookSecret::class,
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

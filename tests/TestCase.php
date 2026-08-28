<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests;

use Appto\TelegramBot\TelegramBotServiceProvider;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Spatie\LaravelData\LaravelDataServiceProvider;

/**
 * Standalone Laravel application bootstrap for testing the package, without a dependency on
 * orchestra/testbench: it boots a real Illuminate\Foundation\Application, wires just the pieces
 * TelegramBotServiceProvider and its dependencies (Eloquent, spatie/laravel-data) need, and runs
 * the package's own migrations against an in-memory SQLite connection.
 */
abstract class TestCase extends BaseTestCase
{
    protected Application $app;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = $this->createApplication();
    }

    protected function tearDown(): void
    {
        Facade::clearResolvedInstances();

        parent::tearDown();
    }

    /**
     * Config merged into the 'telegram-bot' key before the service provider registers.
     * Override in a test class to exercise non-default config (e.g. repository => 'database').
     *
     * @return array<string, mixed>
     */
    protected function telegramBotConfig(): array
    {
        return [
            'bots' => [],
            'repository' => 'config',
            'base_uri' => 'https://api.telegram.org',
            'http' => [],
            'unauthorized' => [
                'message' => null,
                'show_alert' => false,
            ],
        ];
    }

    private function createApplication(): Application
    {
        $basePath = sys_get_temp_dir().'/telegram-bot-tests-'.bin2hex(random_bytes(6));
        mkdir($basePath, recursive: true);

        $app = new Application($basePath);

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);

        // Minimal bindings Foundation\Application needs but doesn't provide out of the box
        // when no full Laravel skeleton (bootstrap/app.php) built it.
        $app->instance('env', 'testing');
        $app->singleton('files', fn () => new Filesystem);

        $app->instance('config', new Repository([
            'app' => [
                'key' => 'base64:'.base64_encode(random_bytes(32)),
                'name' => 'Testing',
            ],
            'database' => [
                'default' => 'testing',
                'connections' => [
                    'testing' => [
                        'driver' => 'sqlite',
                        'database' => ':memory:',
                        'prefix' => '',
                        'foreign_key_constraints' => true,
                    ],
                ],
            ],
            'telegram-bot' => $this->telegramBotConfig(),
        ]));

        $app->register(DatabaseServiceProvider::class);
        $app->register(LaravelDataServiceProvider::class);
        $app->register(TelegramBotServiceProvider::class);

        $app->boot();

        return $app;
    }

    /**
     * Runs the package's telegram_dialog_states migration against the booted in-memory
     * connection. Only the tests that actually touch dialog state need to call this.
     */
    protected function migrateDialogStates(): void
    {
        $migration = require __DIR__.'/../database/migrations/2026_08_14_212213_create_telegram_dialog_states_table.php';
        $migration->up();
    }

    protected function config(): ConfigContract
    {
        return $this->app->make('config');
    }
}

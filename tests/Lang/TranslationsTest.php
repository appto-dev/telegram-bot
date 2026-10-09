<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Lang;

use Appto\TelegramBot\Dialog\CancelCommand;
use Appto\TelegramBot\Routing\HelpCommand;
use Appto\TelegramBot\Tests\TestCase;

final class TranslationsTest extends TestCase
{
    private const string LANG_PATH = __DIR__.'/../../resources/lang';

    /**
     * Every text a bot user can see ships in both languages — a key added to one file only would
     * silently fall back to the raw "telegram-bot::file.key" string in the other locale.
     */
    public function test_every_english_file_has_a_russian_twin_with_the_same_keys(): void
    {
        $englishFiles = glob(self::LANG_PATH.'/en/*.php');

        $this->assertNotEmpty($englishFiles);

        foreach ($englishFiles as $englishFile) {
            $russianFile = self::LANG_PATH.'/ru/'.basename($englishFile);

            $this->assertFileExists($russianFile);
            $this->assertSame(array_keys(require $englishFile), array_keys(require $russianFile), basename($englishFile));
        }
    }

    public function test_built_in_commands_describe_themselves_in_the_current_locale(): void
    {
        $this->app->setLocale('en');
        $this->assertSame('Shows this list of available commands', HelpCommand::description());
        $this->assertSame('Cancel the current dialog', CancelCommand::description());

        $this->app->setLocale('ru');
        $this->assertSame('Показывает список доступных команд', HelpCommand::description());
        $this->assertSame('Отменить текущий диалог', CancelCommand::description());
    }

    public function test_the_unauthorized_message_key_resolves(): void
    {
        $this->app->setLocale('ru');

        $this->assertSame('У вас нет доступа к этой команде.', __('telegram-bot::auth.unauthorized'));
    }
}

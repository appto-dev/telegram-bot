<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Routing;

use Appto\TelegramBot\Routing\CommandRouter;
use Appto\TelegramBot\Tests\Fixtures\DeniedHandler;
use Appto\TelegramBot\Tests\Fixtures\RecordingHandler;
use Appto\TelegramBot\Tests\Support\UpdateFactory;
use Appto\TelegramBot\Tests\TestCase;

final class CommandRouterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        RecordingHandler::reset();
        DeniedHandler::reset();
    }

    public function test_it_matches_a_registered_command(): void
    {
        $router = new CommandRouter;
        $router->add('start', RecordingHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::command('start')));

        $this->assertTrue($matched);
        $this->assertSame(1, RecordingHandler::$calls);
    }

    public function test_it_does_not_match_an_unregistered_command(): void
    {
        $router = new CommandRouter;
        $router->add('start', RecordingHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::command('help')));

        $this->assertFalse($matched);
        $this->assertSame(0, RecordingHandler::$calls);
    }

    /**
     * UpdateContext::command() relies on the bot_command MessageEntity, not string parsing —
     * a plain text message that merely starts with "/" but carries no entity is not a command.
     */
    public function test_a_message_without_a_bot_command_entity_does_not_match(): void
    {
        $router = new CommandRouter;
        $router->add('start', RecordingHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::textMessage('/start')));

        $this->assertFalse($matched);
    }

    /**
     * "/start@shopbot" — the @botname suffix Telegram appends in group chats is stripped.
     */
    public function test_it_strips_the_bot_username_suffix(): void
    {
        $router = new CommandRouter;
        $router->add('start', RecordingHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::command('start', 'shopbot')));

        $this->assertTrue($matched);
        $this->assertSame(1, RecordingHandler::$calls);
    }

    public function test_command_matching_is_case_sensitive(): void
    {
        $router = new CommandRouter;
        $router->add('Start', RecordingHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::command('start')));

        $this->assertFalse($matched);
    }

    public function test_a_matched_but_unauthorized_command_still_reports_as_matched(): void
    {
        $router = new CommandRouter;
        $router->add('secret', DeniedHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::command('secret')));

        $this->assertTrue($matched);
        $this->assertSame(0, DeniedHandler::$handleCalls);
    }

    public function test_all_returns_the_registered_routes(): void
    {
        $router = new CommandRouter;
        $router->add('start', RecordingHandler::class);
        $router->add('help', RecordingHandler::class);

        $this->assertSame(
            ['start' => RecordingHandler::class, 'help' => RecordingHandler::class],
            $router->all(),
        );
    }
}

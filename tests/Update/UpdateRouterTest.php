<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Update;

use Appto\TelegramBot\Tests\Fixtures\RecordingHandler;
use Appto\TelegramBot\Tests\Support\UpdateFactory;
use Appto\TelegramBot\Tests\TestCase;
use Appto\TelegramBot\Update\UpdateRouter;

final class UpdateRouterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        RecordingHandler::reset();
    }

    public function test_it_matches_by_update_type(): void
    {
        $router = new UpdateRouter;
        $router->add('callback_query', RecordingHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::callbackQuery('anything')));

        $this->assertTrue($matched);
        $this->assertSame(1, RecordingHandler::$calls);
    }

    public function test_it_does_not_match_a_different_update_type(): void
    {
        $router = new UpdateRouter;
        $router->add('callback_query', RecordingHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::textMessage('hello')));

        $this->assertFalse($matched);
        $this->assertSame(0, RecordingHandler::$calls);
    }

    /**
     * With no routes registered at all, dispatch() bails out before even inspecting the update —
     * this is what lets it sit safely as the catch-all last in a RouterRegistry.
     */
    public function test_it_returns_false_immediately_when_nothing_is_registered(): void
    {
        $router = new UpdateRouter;

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::textMessage('hello')));

        $this->assertFalse($matched);
    }

    public function test_all_returns_the_registered_routes(): void
    {
        $router = new UpdateRouter;
        $router->add('poll', RecordingHandler::class);

        $this->assertSame(['poll' => RecordingHandler::class], $router->all());
    }
}

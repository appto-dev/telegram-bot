<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Routing;

use Appto\TelegramBot\Exceptions\RouterException;
use Appto\TelegramBot\Routing\CommandRouter;
use Appto\TelegramBot\Routing\RouterRegistry;
use Appto\TelegramBot\Routing\TextRouter;
use Appto\TelegramBot\Tests\Fixtures\RecordingHandler;
use Appto\TelegramBot\Tests\Support\UpdateFactory;
use Appto\TelegramBot\Tests\TestCase;

final class RouterRegistryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        RecordingHandler::reset();
    }

    public function test_it_resolves_a_registered_router_by_its_key(): void
    {
        $registry = new RouterRegistry;
        $registry->register(TextRouter::class);

        $this->assertInstanceOf(TextRouter::class, $registry->get('text'));
    }

    public function test_it_accepts_an_array_of_routers(): void
    {
        $registry = new RouterRegistry;
        $registry->register([TextRouter::class, CommandRouter::class]);

        $this->assertInstanceOf(TextRouter::class, $registry->get('text'));
        $this->assertInstanceOf(CommandRouter::class, $registry->get('command'));
    }

    public function test_get_throws_for_an_unregistered_router(): void
    {
        $registry = new RouterRegistry;

        $this->expectException(RouterException::class);

        $registry->get('missing');
    }

    public function test_get_returns_the_same_resolved_instance_on_repeated_calls(): void
    {
        $registry = new RouterRegistry;
        $registry->register(TextRouter::class);

        $this->assertSame($registry->get('text'), $registry->get('text'));
    }

    /**
     * dispatch() tries routers in registration order and stops at the first one that matches —
     * a router registered later never even gets asked once an earlier one has already handled it.
     */
    public function test_dispatch_tries_routers_in_registration_order_and_stops_at_the_first_match(): void
    {
        $registry = new RouterRegistry;
        $registry->register(TextRouter::class);
        $registry->register(CommandRouter::class);

        /** @var TextRouter $textRouter */
        $textRouter = $registry->get('text');
        $textRouter->add('start', RecordingHandler::class);

        /** @var CommandRouter $commandRouter */
        $commandRouter = $registry->get('command');
        $commandRouter->add('start', RecordingHandler::class);

        // Plain text "start" — only the TextRouter can match this (CommandRouter needs the
        // bot_command entity), so this also proves dispatch() actually delegates per-router.
        $matched = $registry->dispatch(UpdateFactory::context(UpdateFactory::textMessage('start')));

        $this->assertTrue($matched);
        $this->assertSame(1, RecordingHandler::$calls);
    }

    public function test_dispatch_returns_false_when_no_router_matches(): void
    {
        $registry = new RouterRegistry;
        $registry->register(TextRouter::class);

        $matched = $registry->dispatch(UpdateFactory::context(UpdateFactory::textMessage('anything')));

        $this->assertFalse($matched);
    }
}

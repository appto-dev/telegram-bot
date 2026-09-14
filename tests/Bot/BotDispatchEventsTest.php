<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Bot;

use Appto\TelegramBot\Events\UpdateReceived;
use Appto\TelegramBot\Routing\RouterRegistry;
use Appto\TelegramBot\Tests\Fixtures\NoopBot;
use Appto\TelegramBot\Tests\Support\UpdateFactory;
use Appto\TelegramBot\Tests\TestCase;
use Illuminate\Support\Facades\Event;

final class BotDispatchEventsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->migrateDialogStates();
    }

    public function test_dispatch_fires_update_received_even_when_nothing_handles_the_update(): void
    {
        Event::fake([UpdateReceived::class]);

        $bot = UpdateFactory::bot();
        $context = UpdateFactory::context(UpdateFactory::textMessage('hi'), $bot);

        (new NoopBot($bot, new RouterRegistry))->dispatch($context);

        Event::assertDispatched(
            UpdateReceived::class,
            fn (UpdateReceived $event) => $event->context === $context && $event->context->bot === $bot,
        );
    }
}

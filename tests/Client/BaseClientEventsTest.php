<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Client;

use Appto\TelegramBot\Client\TelegramClient;
use Appto\TelegramBot\Events\TelegramApiCallMade;
use Appto\TelegramBot\Events\TelegramApiCallRequested;
use Appto\TelegramBot\Tests\Support\UpdateFactory;
use Appto\TelegramBot\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;

final class BaseClientEventsTest extends TestCase
{
    public function test_call_dispatches_a_requested_event_before_and_a_made_event_after(): void
    {
        Event::fake([TelegramApiCallRequested::class, TelegramApiCallMade::class]);
        Http::fake(['*' => Http::response(['ok' => true, 'result' => ['message_id' => 999]])]);

        $bot = UpdateFactory::bot();
        $client = new TelegramClient($bot, []);

        $client->call('sendMessage', ['chat_id' => 111, 'text' => 'hi']);

        Event::assertDispatched(
            TelegramApiCallRequested::class,
            fn (TelegramApiCallRequested $event) => $event->bot === $bot
                && $event->method === 'sendMessage'
                && $event->parameters === ['chat_id' => 111, 'text' => 'hi'],
        );

        Event::assertDispatched(
            TelegramApiCallMade::class,
            fn (TelegramApiCallMade $event) => $event->bot === $bot
                && $event->method === 'sendMessage'
                && $event->response === ['message_id' => 999],
        );
    }
}

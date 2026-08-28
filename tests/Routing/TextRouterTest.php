<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Routing;

use Appto\TelegramBot\Routing\TextRouter;
use Appto\TelegramBot\Tests\Fixtures\DeniedHandler;
use Appto\TelegramBot\Tests\Fixtures\RecordingHandler;
use Appto\TelegramBot\Tests\Support\UpdateFactory;
use Appto\TelegramBot\Tests\TestCase;

final class TextRouterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        RecordingHandler::reset();
        DeniedHandler::reset();
    }

    public function test_it_matches_an_exact_text(): void
    {
        $router = new TextRouter;
        $router->add('hello', RecordingHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::textMessage('hello')));

        $this->assertTrue($matched);
        $this->assertSame(1, RecordingHandler::$calls);
    }

    public function test_it_does_not_match_a_different_text(): void
    {
        $router = new TextRouter;
        $router->add('hello', RecordingHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::textMessage('goodbye')));

        $this->assertFalse($matched);
        $this->assertSame(0, RecordingHandler::$calls);
    }

    public function test_it_does_not_match_a_substring(): void
    {
        $router = new TextRouter;
        $router->add('hello', RecordingHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::textMessage('hello there')));

        $this->assertFalse($matched);
    }

    /**
     * Registered as `mb_strtolower(trim($text))` — matching must be case-insensitive.
     */
    public function test_matching_is_case_insensitive(): void
    {
        $router = new TextRouter;
        $router->add('Hello', RecordingHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::textMessage('HELLO')));

        $this->assertTrue($matched);
        $this->assertSame(1, RecordingHandler::$calls);
    }

    public function test_matching_trims_surrounding_whitespace(): void
    {
        $router = new TextRouter;
        $router->add('hello', RecordingHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::textMessage("  hello  \n")));

        $this->assertTrue($matched);
    }

    /**
     * A pattern registered with surrounding whitespace/mixed case is normalized the same way,
     * so it still matches a "clean" incoming text.
     */
    public function test_pattern_registration_is_normalized_too(): void
    {
        $router = new TextRouter;
        $router->add('  HeLLo  ', RecordingHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::textMessage('hello')));

        $this->assertTrue($matched);
    }

    public function test_it_falls_back_to_the_caption_when_there_is_no_text(): void
    {
        $router = new TextRouter;
        $router->add('nice photo', RecordingHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::captionedMessage('nice photo')));

        $this->assertTrue($matched);
        $this->assertSame(1, RecordingHandler::$calls);
    }

    public function test_it_does_not_match_when_message_has_neither_text_nor_caption(): void
    {
        $router = new TextRouter;
        $router->add('hello', RecordingHandler::class);

        $payload = UpdateFactory::textMessage('hello');
        unset($payload['message']['text']);

        $matched = $router->dispatch(UpdateFactory::context($payload));

        $this->assertFalse($matched);
    }

    /**
     * A route match that is authorization-denied still reports as "matched" (routing succeeded),
     * it's HandlerInvoker that short-circuits before calling handle().
     */
    public function test_a_matched_but_unauthorized_route_still_reports_as_matched(): void
    {
        $router = new TextRouter;
        $router->add('secret', DeniedHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::textMessage('secret')));

        $this->assertTrue($matched);
        $this->assertSame(0, DeniedHandler::$handleCalls);
    }

    public function test_all_returns_the_registered_routes(): void
    {
        $router = new TextRouter;
        $router->add('hello', RecordingHandler::class);
        $router->add('bye', RecordingHandler::class);

        $this->assertSame(
            ['hello' => RecordingHandler::class, 'bye' => RecordingHandler::class],
            $router->all(),
        );
    }
}

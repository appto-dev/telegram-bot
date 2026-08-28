<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Routing;

use Appto\TelegramBot\Routing\CallbackRouter;
use Appto\TelegramBot\Tests\Fixtures\RecordingCallbackHandler;
use Appto\TelegramBot\Tests\Support\UpdateFactory;
use Appto\TelegramBot\Tests\TestCase;

final class CallbackRouterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        RecordingCallbackHandler::reset();
    }

    public function test_it_matches_a_single_required_param(): void
    {
        $router = new CallbackRouter;
        $router->add('order:confirm:{id}', RecordingCallbackHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::callbackQuery('order:confirm:42')));

        $this->assertTrue($matched);
        $this->assertSame(['id' => '42'], RecordingCallbackHandler::$lastParams);
    }

    public function test_a_literal_pattern_with_no_params_matches_exactly(): void
    {
        $router = new CallbackRouter;
        $router->add('cancel', RecordingCallbackHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::callbackQuery('cancel')));

        $this->assertTrue($matched);
        $this->assertSame([], RecordingCallbackHandler::$lastParams);
    }

    public function test_it_does_not_match_a_different_literal_prefix(): void
    {
        $router = new CallbackRouter;
        $router->add('order:confirm:{id}', RecordingCallbackHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::callbackQuery('order:cancel:42')));

        $this->assertFalse($matched);
    }

    /**
     * A required {name} placeholder needs at least one character — an empty segment doesn't match.
     */
    public function test_a_required_param_does_not_match_an_empty_segment(): void
    {
        $router = new CallbackRouter;
        $router->add('order:confirm:{id}', RecordingCallbackHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::callbackQuery('order:confirm:')));

        $this->assertFalse($matched);
    }

    /**
     * An optional {name?} param is filled in with its value when present in the data...
     */
    public function test_an_optional_param_is_captured_when_present(): void
    {
        $router = new CallbackRouter;
        $router->add('order:{id}:{note?}', RecordingCallbackHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::callbackQuery('order:42:urgent')));

        $this->assertTrue($matched);
        $this->assertSame(['id' => '42', 'note' => 'urgent'], RecordingCallbackHandler::$lastParams);
    }

    /**
     * ...and comes back as null (still present in the params array) when the data stops short.
     */
    public function test_an_optional_param_is_null_when_absent(): void
    {
        $router = new CallbackRouter;
        $router->add('order:{id}:{note?}', RecordingCallbackHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::callbackQuery('order:42')));

        $this->assertTrue($matched);
        $this->assertSame(['id' => '42', 'note' => null], RecordingCallbackHandler::$lastParams);
    }

    public function test_it_does_not_match_when_the_update_has_no_callback_query(): void
    {
        $router = new CallbackRouter;
        $router->add('cancel', RecordingCallbackHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::textMessage('cancel')));

        $this->assertFalse($matched);
        $this->assertSame(0, RecordingCallbackHandler::$calls);
    }

    public function test_first_matching_pattern_wins_in_registration_order(): void
    {
        $router = new CallbackRouter;
        $router->add('order:{id}', RecordingCallbackHandler::class);
        $router->add('order:confirm:{id}', RecordingCallbackHandler::class);

        $matched = $router->dispatch(UpdateFactory::context(UpdateFactory::callbackQuery('order:confirm:42')));

        $this->assertTrue($matched);
        // "order:{id}" is registered first and its lazy `.+?` still matches the whole payload.
        $this->assertSame(['id' => 'confirm:42'], RecordingCallbackHandler::$lastParams);
    }

    public function test_all_returns_the_registered_routes(): void
    {
        $router = new CallbackRouter;
        $router->add('cancel', RecordingCallbackHandler::class);
        $router->add('order:{id}', RecordingCallbackHandler::class);

        $this->assertSame(
            ['cancel' => RecordingCallbackHandler::class, 'order:{id}' => RecordingCallbackHandler::class],
            $router->all(),
        );
    }
}

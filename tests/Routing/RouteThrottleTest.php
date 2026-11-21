<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Routing;

use Appto\TelegramBot\Exceptions\ChatThrottleExceededException;
use Appto\TelegramBot\Exceptions\UserThrottleExceededException;
use Appto\TelegramBot\Routing\CallbackRouter;
use Appto\TelegramBot\Routing\CommandRouter;
use Appto\TelegramBot\Routing\RouteDefinition;
use Appto\TelegramBot\Routing\TextRouter;
use Appto\TelegramBot\Tests\Fixtures\RecordingCallbackHandler;
use Appto\TelegramBot\Tests\Fixtures\RecordingHandler;
use Appto\TelegramBot\Tests\Support\UpdateFactory;
use Appto\TelegramBot\Tests\TestCase;

final class RouteThrottleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        RecordingHandler::reset();
        RecordingCallbackHandler::reset();
    }

    public function test_it_throttles_a_matched_command_per_user(): void
    {
        $router = new CommandRouter;
        $router->add('start', RecordingHandler::class)->throttle(1, 60);

        $context = UpdateFactory::context(UpdateFactory::command('start', chatId: 111, userId: 222));

        $this->assertTrue($router->dispatch($context));
        $this->assertSame(1, RecordingHandler::$calls);

        try {
            $router->dispatch($context);
            $this->fail('Expected UserThrottleExceededException was not thrown.');
        } catch (UserThrottleExceededException $exception) {
            $this->assertSame($context, $exception->context);
            $this->assertGreaterThan(0, $exception->retryAfter);
        }

        $this->assertSame(1, RecordingHandler::$calls);
    }

    public function test_different_routes_have_separate_throttle_buckets(): void
    {
        $router = new CommandRouter;
        $router->add('start', RecordingHandler::class)->throttle(1, 60);
        $router->add('help', RecordingHandler::class)->throttle(1, 60);

        $start = UpdateFactory::context(UpdateFactory::command('start', chatId: 111, userId: 222));
        $help = UpdateFactory::context(UpdateFactory::command('help', chatId: 111, userId: 222));

        $this->assertTrue($router->dispatch($start));

        $this->expectException(UserThrottleExceededException::class);
        $router->dispatch($start);

        // The "help" route is a different bucket, so it must still be allowed.
    }

    public function test_a_different_route_is_not_blocked_by_another_routes_limit(): void
    {
        $router = new CommandRouter;
        $router->add('start', RecordingHandler::class)->throttle(1, 60);
        $router->add('help', RecordingHandler::class)->throttle(1, 60);

        $start = UpdateFactory::context(UpdateFactory::command('start', chatId: 111, userId: 222));
        $help = UpdateFactory::context(UpdateFactory::command('help', chatId: 111, userId: 222));

        $this->assertTrue($router->dispatch($start));

        try {
            $router->dispatch($start);
            $this->fail('Expected UserThrottleExceededException was not thrown.');
        } catch (UserThrottleExceededException) {
        }

        $this->assertTrue($router->dispatch($help));
        $this->assertSame(2, RecordingHandler::$calls);
    }

    public function test_it_throttles_a_matched_route_per_chat(): void
    {
        $router = new TextRouter;
        $router->add('ping', RecordingHandler::class)->throttlePerChat(1, 60);

        $firstUser = UpdateFactory::context(UpdateFactory::textMessage('ping', chatId: 111, userId: 222));
        $secondUser = UpdateFactory::context(UpdateFactory::textMessage('ping', chatId: 111, userId: 333));

        $this->assertTrue($router->dispatch($firstUser));

        $this->expectException(ChatThrottleExceededException::class);
        $router->dispatch($secondUser);
    }

    public function test_a_non_matching_request_does_not_consume_the_route_limit(): void
    {
        $router = new CommandRouter;
        $router->add('start', RecordingHandler::class)->throttle(1, 60);

        $this->assertFalse($router->dispatch(UpdateFactory::context(UpdateFactory::command('help', chatId: 111, userId: 222))));
        $this->assertTrue($router->dispatch(UpdateFactory::context(UpdateFactory::command('start', chatId: 111, userId: 222))));
        $this->assertSame(1, RecordingHandler::$calls);
    }

    public function test_it_throttles_a_matched_callback_route(): void
    {
        $router = new CallbackRouter;
        $router->add('order:{id}', RecordingCallbackHandler::class)->throttle(1, 60);

        $context = UpdateFactory::context(UpdateFactory::callbackQuery('order:42', chatId: 111, userId: 222));

        $this->assertTrue($router->dispatch($context));
        $this->assertSame(['id' => '42'], RecordingCallbackHandler::$lastParams);

        $this->expectException(UserThrottleExceededException::class);
        $router->dispatch($context);
    }

    public function test_route_definition_is_fluent_and_exposes_throttle_config(): void
    {
        $router = new CommandRouter;
        $route = $router->add('start', RecordingHandler::class);

        $this->assertInstanceOf(RouteDefinition::class, $route);
        $this->assertSame($route, $route->throttle(2, 30));
        $this->assertSame(['scope' => 'user', 'max' => 2, 'decay' => 30], $route->throttleConfig());

        $route->throttlePerChat(3, 45);
        $this->assertSame(['scope' => 'chat', 'max' => 3, 'decay' => 45], $route->throttleConfig());
    }
}

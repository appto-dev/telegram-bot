<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Bot;

use Appto\TelegramBot\Exceptions\ThrottleExceededException;
use Appto\TelegramBot\Exceptions\UserThrottleExceededException;
use Appto\TelegramBot\Routing\RouterRegistry;
use Appto\TelegramBot\Tests\Fixtures\ThrowingMiddlewareBot;
use Appto\TelegramBot\Tests\Support\UpdateFactory;
use Appto\TelegramBot\Tests\TestCase;
use Illuminate\Contracts\Debug\ExceptionHandler;

final class BotThrottleTest extends TestCase
{
    public function test_a_throttle_exception_never_escapes_dispatch(): void
    {
        $bot = new ThrowingMiddlewareBot(UpdateFactory::bot(), new RouterRegistry);

        $bot->dispatch(UpdateFactory::context(UpdateFactory::textMessage('hi')));

        $this->addToAssertionCount(1);
    }

    public function test_it_is_ignored_by_the_exception_handler_by_default(): void
    {
        $handler = $this->app->make(ExceptionHandler::class);

        $this->assertFalse($handler->shouldReport(new UserThrottleExceededException(
            UpdateFactory::context(UpdateFactory::textMessage('hi')),
            5,
        )));
    }

    public function test_an_app_can_opt_back_in_via_stop_ignoring_and_reportable(): void
    {
        $handler = $this->app->make(ExceptionHandler::class);
        $handler->stopIgnoring(ThrottleExceededException::class);

        $captured = null;
        $handler->reportable(function (UserThrottleExceededException $exception) use (&$captured): void {
            $captured = $exception;
        });

        $bot = new ThrowingMiddlewareBot(UpdateFactory::bot(), new RouterRegistry);
        $context = UpdateFactory::context(UpdateFactory::textMessage('hi'));

        $bot->dispatch($context);

        $this->assertInstanceOf(UserThrottleExceededException::class, $captured);
        $this->assertSame($context, $captured->context);
        $this->assertSame(5, $captured->retryAfter);
    }
}

<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Exceptions;

use Appto\TelegramBot\Exceptions\UserThrottleExceededException;
use Appto\TelegramBot\Tests\Fixtures\WrappingExceptionHandler;
use Appto\TelegramBot\Tests\Support\UpdateFactory;
use Appto\TelegramBot\Tests\TestCase;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;

final class ConsoleWrappedHandlerTest extends TestCase
{
    protected function beforeBoot(Application $app): void
    {
        // What Collision does in console: resolve the real handler, then rebind the contract to a wrapper.
        $inner = $app->make(ExceptionHandler::class);
        $app->singleton(ExceptionHandler::class, fn () => new WrappingExceptionHandler($inner));
    }

    public function test_the_app_boots_and_the_wrapped_handler_still_ignores_throttling(): void
    {
        $handler = $this->app->make(ExceptionHandler::class);

        $this->assertInstanceOf(WrappingExceptionHandler::class, $handler);
        $this->assertFalse($handler->shouldReport(new UserThrottleExceededException(
            UpdateFactory::context(UpdateFactory::textMessage('hi')),
            5,
        )));
    }
}

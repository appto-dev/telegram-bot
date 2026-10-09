<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Exceptions;

use Appto\TelegramBot\Exceptions\ThrottleExceededException;
use Appto\TelegramBot\Exceptions\UserThrottleExceededException;
use Appto\TelegramBot\Tests\Support\UpdateFactory;
use Appto\TelegramBot\Tests\TestCase;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Exceptions\Handler;

final class AppStopIgnoringTest extends TestCase
{
    protected function beforeBoot(Application $app): void
    {
        // Same hook ApplicationBuilder::withExceptions() registers for bootstrap/app.php.
        $app->afterResolving(Handler::class, fn (Handler $handler) => $handler->stopIgnoring(ThrottleExceededException::class));
    }

    public function test_stop_ignoring_from_bootstrap_app_wins_over_the_package_default(): void
    {
        $handler = $this->app->make(ExceptionHandler::class);

        $this->assertTrue($handler->shouldReport(new UserThrottleExceededException(
            UpdateFactory::context(UpdateFactory::textMessage('hi')),
            5,
        )));
    }
}

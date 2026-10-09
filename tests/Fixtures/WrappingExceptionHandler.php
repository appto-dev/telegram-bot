<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Fixtures;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Throwable;

/**
 * Stand-in for NunoMaduro\Collision\Adapters\Laravel\ExceptionHandler: implements only the
 * contract, so it has no dontReport()/stopIgnoring() of the wrapped Foundation handler.
 */
final class WrappingExceptionHandler implements ExceptionHandler
{
    public function __construct(public readonly ExceptionHandler $inner) {}

    public function report(Throwable $e)
    {
        $this->inner->report($e);
    }

    public function shouldReport(Throwable $e)
    {
        return $this->inner->shouldReport($e);
    }

    public function render($request, Throwable $e)
    {
        return $this->inner->render($request, $e);
    }

    public function renderForConsole($output, Throwable $e)
    {
        $this->inner->renderForConsole($output, $e);
    }
}

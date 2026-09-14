<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Support;

use Appto\TelegramBot\Support\HandlerInvoker;
use Appto\TelegramBot\Tests\Fixtures\DeniedHandler;
use Appto\TelegramBot\Tests\Fixtures\RecordingHandler;
use Appto\TelegramBot\Tests\TestCase;
use Appto\TelegramBot\Update\UpdateContext;

final class HandlerInvokerTest extends TestCase
{
    protected function tearDown(): void
    {
        RecordingHandler::reset();
        DeniedHandler::reset();

        parent::tearDown();
    }

    public function test_it_resolves_and_calls_a_class_string_handler(): void
    {
        $context = UpdateFactory::context(UpdateFactory::textMessage('hi'));

        HandlerInvoker::call(RecordingHandler::class, ['context' => $context]);

        $this->assertSame(1, RecordingHandler::$calls);
        $this->assertSame($context, RecordingHandler::$lastContext);
    }

    public function test_it_invokes_a_plain_callable_with_container_resolution(): void
    {
        $context = UpdateFactory::context(UpdateFactory::textMessage('hi'));
        $received = null;

        HandlerInvoker::call(function (UpdateContext $context) use (&$received): void {
            $received = $context;
        }, ['context' => $context]);

        $this->assertSame($context, $received);
    }

    public function test_it_passes_extra_route_parameters_through(): void
    {
        $context = UpdateFactory::context(UpdateFactory::textMessage('hi'));
        $seen = [];

        HandlerInvoker::call(function (UpdateContext $context, string $id) use (&$seen): void {
            $seen = [$context, $id];
        }, ['context' => $context, 'id' => '42']);

        $this->assertSame([$context, '42'], $seen);
    }

    public function test_it_skips_a_handler_that_denies_authorization(): void
    {
        $context = UpdateFactory::context(UpdateFactory::textMessage('hi'));

        HandlerInvoker::call(DeniedHandler::class, ['context' => $context]);

        $this->assertSame(0, DeniedHandler::$handleCalls);
    }

    public function test_it_rejects_parameters_missing_a_context(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Context must be an instance of UpdateContext.');

        HandlerInvoker::call(RecordingHandler::class, []);
    }
}

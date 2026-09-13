<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Update;

use Appto\TelegramBot\Exceptions\UserThrottleExceededException;
use Appto\TelegramBot\Tests\Support\UpdateFactory;
use Appto\TelegramBot\Tests\TestCase;
use Appto\TelegramBot\Type\Update;
use Appto\TelegramBot\Update\ThrottleMiddleware;
use Appto\TelegramBot\Update\UpdateContext;
use Illuminate\Support\Facades\RateLimiter;

final class ThrottleMiddlewareTest extends TestCase
{
    public function test_it_allows_updates_within_the_limit(): void
    {
        $middleware = new ThrottleMiddleware;
        $calls = 0;
        $next = function () use (&$calls): void {
            $calls++;
        };

        $context = UpdateFactory::context(UpdateFactory::textMessage('hi', userId: 1));

        $middleware->handle($context, $next, '2', '60');
        $middleware->handle($context, $next, '2', '60');

        $this->assertSame(2, $calls);
    }

    public function test_it_throws_once_the_limit_is_exceeded(): void
    {
        $middleware = new ThrottleMiddleware;
        $calls = 0;
        $next = function () use (&$calls): void {
            $calls++;
        };

        $context = UpdateFactory::context(UpdateFactory::textMessage('hi', userId: 2));

        $middleware->handle($context, $next, '2', '60');
        $middleware->handle($context, $next, '2', '60');

        $this->expectException(UserThrottleExceededException::class);

        try {
            $middleware->handle($context, $next, '2', '60');
        } finally {
            $this->assertSame(2, $calls);
        }
    }

    public function test_different_users_have_independent_limits(): void
    {
        $middleware = new ThrottleMiddleware;
        $calls = 0;
        $next = function () use (&$calls): void {
            $calls++;
        };

        $middleware->handle(UpdateFactory::context(UpdateFactory::textMessage('hi', userId: 10)), $next, '1', '60');
        $middleware->handle(UpdateFactory::context(UpdateFactory::textMessage('hi', userId: 11)), $next, '1', '60');

        $this->assertSame(2, $calls);
    }

    public function test_it_fails_open_when_the_update_has_no_identifiable_sender(): void
    {
        $middleware = new ThrottleMiddleware;
        $calls = 0;
        $next = function () use (&$calls): void {
            $calls++;
        };

        $bot = UpdateFactory::bot();
        $context = new UpdateContext($bot, Update::from([
            'update_id' => 1,
            'poll' => [
                'id' => '1',
                'question' => 'q',
                'question_entities' => null,
                'options' => [],
                'total_voter_count' => 0,
                'is_closed' => false,
                'is_anonymous' => true,
                'type' => 'regular',
                'allows_multiple_answers' => false,
                'allows_revoting' => false,
                'members_only' => false,
            ],
        ]));

        for ($i = 0; $i < 5; $i++) {
            $middleware->handle($context, $next, '1', '60');
        }

        $this->assertSame(5, $calls);
    }

    public function test_the_exception_carries_the_context_and_retry_after(): void
    {
        $middleware = new ThrottleMiddleware;
        $next = fn () => null;
        $context = UpdateFactory::context(UpdateFactory::textMessage('hi', userId: 20));

        $middleware->handle($context, $next, '1', '30');

        try {
            $middleware->handle($context, $next, '1', '30');
            $this->fail('Expected UserThrottleExceededException to be thrown.');
        } catch (UserThrottleExceededException $exception) {
            $this->assertSame($context, $exception->context);
            $this->assertGreaterThan(0, $exception->retryAfter);
            $this->assertLessThanOrEqual(30, $exception->retryAfter);
        }
    }

    protected function tearDown(): void
    {
        RateLimiter::clear('tg:throttle:test-bot:user:1');
        RateLimiter::clear('tg:throttle:test-bot:user:2');
        RateLimiter::clear('tg:throttle:test-bot:user:10');
        RateLimiter::clear('tg:throttle:test-bot:user:11');
        RateLimiter::clear('tg:throttle:test-bot:user:20');

        parent::tearDown();
    }
}

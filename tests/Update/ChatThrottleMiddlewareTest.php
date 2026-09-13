<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Update;

use Appto\TelegramBot\Exceptions\ChatThrottleExceededException;
use Appto\TelegramBot\Tests\Support\UpdateFactory;
use Appto\TelegramBot\Tests\TestCase;
use Appto\TelegramBot\Update\ChatThrottleMiddleware;
use Illuminate\Support\Facades\RateLimiter;

final class ChatThrottleMiddlewareTest extends TestCase
{
    public function test_different_users_in_the_same_chat_share_one_limit(): void
    {
        $middleware = new ChatThrottleMiddleware;
        $calls = 0;
        $next = function () use (&$calls): void {
            $calls++;
        };

        $middleware->handle(UpdateFactory::context(UpdateFactory::textMessage('hi', chatId: 500, userId: 1)), $next, '2', '60');
        $middleware->handle(UpdateFactory::context(UpdateFactory::textMessage('hi', chatId: 500, userId: 2)), $next, '2', '60');

        $this->assertSame(2, $calls);

        $this->expectException(ChatThrottleExceededException::class);
        $middleware->handle(UpdateFactory::context(UpdateFactory::textMessage('hi', chatId: 500, userId: 3)), $next, '2', '60');
    }

    public function test_different_chats_have_independent_limits(): void
    {
        $middleware = new ChatThrottleMiddleware;
        $calls = 0;
        $next = function () use (&$calls): void {
            $calls++;
        };

        $middleware->handle(UpdateFactory::context(UpdateFactory::textMessage('hi', chatId: 600, userId: 1)), $next, '1', '60');
        $middleware->handle(UpdateFactory::context(UpdateFactory::textMessage('hi', chatId: 601, userId: 1)), $next, '1', '60');

        $this->assertSame(2, $calls);
    }

    protected function tearDown(): void
    {
        RateLimiter::clear('tg:throttle:test-bot:chat:500');
        RateLimiter::clear('tg:throttle:test-bot:chat:600');
        RateLimiter::clear('tg:throttle:test-bot:chat:601');

        parent::tearDown();
    }
}

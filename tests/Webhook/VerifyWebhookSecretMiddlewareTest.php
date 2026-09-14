<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Webhook;

use Appto\TelegramBot\Bot\BotManager;
use Appto\TelegramBot\Tests\TestCase;
use Appto\TelegramBot\Webhook\VerifyWebhookSecretMiddleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class VerifyWebhookSecretMiddlewareTest extends TestCase
{
    protected function telegramBotConfig(): array
    {
        $config = parent::telegramBotConfig();
        $config['bots'] = [
            'guarded' => ['token' => 't', 'webhook_secret' => 'top-secret', 'handler' => \stdClass::class],
            'open' => ['token' => 't', 'webhook_secret' => null, 'handler' => \stdClass::class],
        ];

        return $config;
    }

    public function test_it_passes_through_and_attaches_the_bot_identity_when_the_secret_matches(): void
    {
        $middleware = $this->app->make(VerifyWebhookSecretMiddleware::class);
        $request = $this->requestFor('guarded', secretHeader: 'top-secret');
        $response = new Response;

        $result = $middleware->handle($request, fn () => $response);

        $this->assertSame($response, $result);
        $this->assertSame('guarded', $request->attributes->get('telegramBotIdentity')->id);
    }

    public function test_it_rejects_a_missing_secret_header(): void
    {
        $middleware = $this->app->make(VerifyWebhookSecretMiddleware::class);
        $request = $this->requestFor('guarded', secretHeader: null);

        try {
            $middleware->handle($request, fn () => new Response);
            $this->fail('Expected an HttpException.');
        } catch (HttpException $exception) {
            $this->assertSame(401, $exception->getStatusCode());
        }
    }

    public function test_it_rejects_a_wrong_secret_header(): void
    {
        $middleware = $this->app->make(VerifyWebhookSecretMiddleware::class);
        $request = $this->requestFor('guarded', secretHeader: 'wrong');

        $this->expectException(HttpException::class);

        $middleware->handle($request, fn () => new Response);
    }

    public function test_it_passes_through_when_the_bot_has_no_webhook_secret_configured(): void
    {
        $middleware = $this->app->make(VerifyWebhookSecretMiddleware::class);
        $request = $this->requestFor('open', secretHeader: null);
        $response = new Response;

        $result = $middleware->handle($request, fn () => $response);

        $this->assertSame($response, $result);
    }

    public function test_it_throws_for_an_unknown_bot_id(): void
    {
        $middleware = $this->app->make(BotManager::class);
        $request = $this->requestFor('missing', secretHeader: null);

        $this->expectException(\InvalidArgumentException::class);

        (new VerifyWebhookSecretMiddleware($middleware))->handle($request, fn () => new Response);
    }

    private function requestFor(string $botId, ?string $secretHeader): Request
    {
        $request = Request::create('/telegram/webhook/'.$botId, 'POST');

        if ($secretHeader !== null) {
            $request->headers->set('X-Telegram-Bot-Api-Secret-Token', $secretHeader);
        }

        $request->setRouteResolver(fn () => new class($botId)
        {
            public function __construct(private string $botId) {}

            public function parameter(string $name, mixed $default = null): mixed
            {
                return $this->botId;
            }
        });

        return $request;
    }
}

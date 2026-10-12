<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Webhook;

use Appto\TelegramBot\Tests\TestCase;
use Appto\TelegramBot\Webhook\VerifyWebhookSecretMiddleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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

    public function test_it_rejects_a_missing_secret_header_with_a_200_json_error(): void
    {
        $middleware = $this->app->make(VerifyWebhookSecretMiddleware::class);
        $request = $this->requestFor('guarded', secretHeader: null);

        $result = $middleware->handle($request, fn () => $this->fail('The request must not reach the controller.'));

        $this->assertJsonError($result, 'Invalid webhook secret', Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_rejects_a_wrong_secret_header_with_a_200_json_error(): void
    {
        $middleware = $this->app->make(VerifyWebhookSecretMiddleware::class);
        $request = $this->requestFor('guarded', secretHeader: 'wrong');

        $result = $middleware->handle($request, fn () => $this->fail('The request must not reach the controller.'));

        $this->assertJsonError($result, 'Invalid webhook secret', Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_passes_through_when_the_bot_has_no_webhook_secret_configured(): void
    {
        $middleware = $this->app->make(VerifyWebhookSecretMiddleware::class);
        $request = $this->requestFor('open', secretHeader: null);
        $response = new Response;

        $result = $middleware->handle($request, fn () => $response);

        $this->assertSame($response, $result);
    }

    public function test_it_returns_a_200_json_error_for_an_unknown_bot_id(): void
    {
        $middleware = $this->app->make(VerifyWebhookSecretMiddleware::class);
        $request = $this->requestFor('missing', secretHeader: null);

        $result = $middleware->handle($request, fn () => $this->fail('The request must not reach the controller.'));

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertSame(Response::HTTP_OK, $result->getStatusCode());
        $this->assertSame(Response::HTTP_NOT_FOUND, $result->getData(true)['status_code']);
        $this->assertNotEmpty($result->getData(true)['error']);
        $this->assertNull($request->attributes->get('telegramBotIdentity'));
    }

    /**
     * Telegram retries any non-2xx webhook response, so verification errors are reported in the body with HTTP 200.
     */
    private function assertJsonError(Response $response, string $error, int $statusCode): void
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame(['error' => $error, 'status_code' => $statusCode], $response->getData(true));
    }

    private function requestFor(string $botId, ?string $secretHeader): Request
    {
        $request = Request::create('/api/telegram/webhook/'.$botId, 'POST');

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

<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client;

use Appto\TelegramBot\Bot\BotIdentity;
use Appto\TelegramBot\Events\TelegramApiCallMade;
use Appto\TelegramBot\Exceptions\TelegramApiException;
use Appto\TelegramBot\Type\InputFile;
use Appto\TelegramBot\Type\ResponseParameters;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class BaseClient
{
    private PendingRequest $http;

    private ?array $pendingOptions = null;

    public function __construct(
        public readonly BotIdentity $identity,
        array $httpConfig = [],
    ) {
        $baseUrl = rtrim(config('telegram-bot.base_uri'), '/').'/bot'.$this->identity->token;
        $this->http = Http::baseUrl($baseUrl)->retry(3)->withOptions($httpConfig);
    }

    public function call(string $method, array $parameters = []): bool|int|string|array
    {
        $parameters = $this->normalizeParameters($parameters);

        $withMultipart = false;
        $parameters = $this->extractAttachments($parameters, $withMultipart);

        if ($this->pendingOptions) {
            $this->http->withOptions($this->pendingOptions);
            $this->pendingOptions = null;
        }

        if ($withMultipart) {
            foreach ($parameters as $name => $value) {
                if ($value instanceof InputFile) {
                    $this->http->attach($name, $value->toResource(), $value->getFilename());
                } else {
                    $this->http->attach($name, is_array($value)
                        ? json_encode($value, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
                        : $value);
                }
            }
            $parameters = [];
        } else {
            $this->http->asJson();
        }

        try {
            $response = $this->http->post($method, $parameters)->throw();
        } catch (RequestException $exception) {
            $payload = $exception->response->json() ?? [];

            throw new TelegramApiException(description: $payload['description'] ?? 'Unknown error', errorCode: $payload['error_code'] ?? 0, method: $method, parameters: isset($payload['parameters']) ? ResponseParameters::from($payload['parameters']) : null);
        }

        $payload = $response->json();

        $result = $payload['result'] ?? (bool) $payload['ok'];

        event(new TelegramApiCallMade($method, $result));

        return $result;
    }

    public function forNextRequest(array $options): self
    {
        $this->pendingOptions = $options;

        return $this;
    }

    private function normalizeParameters(array $parameters): array
    {
        $normalized = array_map(
            fn ($value) => match (true) {
                $value instanceof Arrayable => $this->normalizeParameters($value->toArray()),
                is_array($value) => $this->normalizeParameters($value),
                default => $value,
            },
            $parameters,
        );

        $filtered = array_filter($normalized, fn ($value) => ! is_null($value));

        return array_is_list($normalized) ? array_values($filtered) : $filtered;
    }

    private function extractAttachments(array $parameters, &$withMultipart): array
    {
        foreach ($parameters as $key => $value) {
            if ($value instanceof InputFile || isset($value['attach'])) {
                /* @var FileInput $value */
                $parameters[$key] = $value->getAttachName();
                $parameters[$value->getFilename()] = $value;

                $withMultipart = true;

                continue;
            }

            if (is_array($value)) {
                $parameters[$key] = $this->extractAttachments($value, $withMultipart);
            }
        }

        return $parameters;
    }
}

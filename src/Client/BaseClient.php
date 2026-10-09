<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client;

use Appto\TelegramBot\Bot\BotIdentity;
use Appto\TelegramBot\Events\TelegramApiCallMade;
use Appto\TelegramBot\Events\TelegramApiCallRequested;
use Appto\TelegramBot\Exceptions\TelegramApiException;
use Appto\TelegramBot\Type\InputFile;
use Appto\TelegramBot\Type\ResponseParameters;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Client\ConnectionException;
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
        $this->http = Http::baseUrl($baseUrl)
            ->retry(times: 3, when: fn ($exception) => $exception instanceof ConnectionException)
            ->withOptions($httpConfig);
    }

    public function call(string $method, array $parameters = []): bool|int|string|array
    {
        event(new TelegramApiCallRequested($this->identity, $method, $parameters));

        $parameters = $this->normalizeParameters($parameters);

        $attachments = [];
        $parameters = $this->extractAttachments($parameters, $attachments);

        if ($this->pendingOptions) {
            $this->http->withOptions($this->pendingOptions);
            $this->pendingOptions = null;
        }

        if ($attachments) {
            foreach ($parameters as $name => $value) {
                $this->http->attach($name, is_array($value)
                    ? json_encode($value, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
                    : $value);
            }

            foreach ($attachments as $name => $file) {
                $this->http->attach($name, $file->toResource(), $file->getFilename());
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

        event(new TelegramApiCallMade($this->identity, $method, $result));

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

    /**
     * Replaces every InputFile, at any nesting level (e.g. InputMedia inside sendMediaGroup), with its
     * "attach://<name>" reference and collects the file itself for the top level of the multipart body.
     *
     * @param  array<string, InputFile>  $attachments
     */
    private function extractAttachments(array $parameters, array &$attachments): array
    {
        foreach ($parameters as $key => $value) {
            if ($value instanceof InputFile) {
                /* @var Upload $value */
                $parameters[$key] = $value->getAttachName();
                $attachments[$value->getFilename()] = $value;

                continue;
            }

            if (is_array($value)) {
                $parameters[$key] = $this->extractAttachments($value, $attachments);
            }
        }

        return $parameters;
    }
}

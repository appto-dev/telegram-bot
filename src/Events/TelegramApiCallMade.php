<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Events;

final readonly class TelegramApiCallMade
{
    public function __construct(
        public string $method,
        public array|bool $response,
    ) {}
}

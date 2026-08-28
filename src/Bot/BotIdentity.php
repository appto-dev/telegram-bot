<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Bot;

use Spatie\LaravelData\Dto;

final class BotIdentity extends Dto
{
    public function __construct(
        public string $id,
        #[\SensitiveParameter]
        public string $token,
        #[\SensitiveParameter]
        public ?string $webhook_secret,
        /** @var class-string<Bot> */
        public string $handler,
    ) {}
}

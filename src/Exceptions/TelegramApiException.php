<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Exceptions;

use Appto\TelegramBot\Type\ResponseParameters;

final class TelegramApiException extends \RuntimeException
{
    public function __construct(
        string $description,
        public readonly int $errorCode,
        public readonly string $method,
        public readonly ?ResponseParameters $parameters = null,
    ) {
        parent::__construct("Telegram API error on [{$method}]: {$description} (code {$errorCode})");
    }
}

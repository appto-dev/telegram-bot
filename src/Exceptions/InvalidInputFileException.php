<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Exceptions;

final class InvalidInputFileException extends \RuntimeException
{
    public static function notFound(string $path): self
    {
        return new self("File not found: {$path}");
    }

    public static function empty(string $path): self
    {
        return new self("File is empty: {$path}");
    }

    public static function unreadable(string $path): self
    {
        return new self("Unable to open file for reading: {$path}");
    }
}

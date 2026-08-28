<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Contracts;

interface HasDescription
{
    public static function description(): string;
}

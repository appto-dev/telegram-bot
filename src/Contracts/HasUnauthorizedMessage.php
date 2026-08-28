<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Contracts;

use Appto\TelegramBot\Update\UpdateContext;

interface HasUnauthorizedMessage
{
    public function unauthorizedMessage(UpdateContext $context): ?string;
}

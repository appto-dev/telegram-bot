<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Contracts;

use Appto\TelegramBot\Update\UpdateContext;

interface CallbackHandler
{
    public function handle(UpdateContext $context, array $params): void;
}

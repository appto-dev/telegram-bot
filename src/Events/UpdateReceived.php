<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Events;

use Appto\TelegramBot\Update\UpdateContext;

final readonly class UpdateReceived
{
    public function __construct(
        public UpdateContext $context,
    ) {}
}

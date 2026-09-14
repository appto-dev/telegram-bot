<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Events;

use Appto\TelegramBot\Dialog\Dialog;
use Appto\TelegramBot\Update\UpdateContext;

final readonly class DialogStarted
{
    public function __construct(
        public UpdateContext $context,
        public Dialog $dialog,
    ) {}
}

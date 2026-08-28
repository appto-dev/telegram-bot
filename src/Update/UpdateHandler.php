<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Update;

interface UpdateHandler
{
    public function handle(UpdateContext $context): void;
}

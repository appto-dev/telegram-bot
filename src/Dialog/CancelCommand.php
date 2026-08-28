<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Dialog;

use Appto\TelegramBot\Update\UpdateContext;
use Appto\TelegramBot\Update\UpdateHandler;

final readonly class CancelCommand implements UpdateHandler
{
    public function __construct(private DialogManager $manager) {}

    public function handle(UpdateContext $context): void
    {
        if (! $this->manager->isActive($context)) {
            return;
        }

        $this->manager->cancel($context);
    }
}

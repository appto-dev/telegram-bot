<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Dialog;

use Appto\TelegramBot\Contracts\HasDescription;
use Appto\TelegramBot\Update\UpdateContext;
use Appto\TelegramBot\Update\UpdateHandler;

final readonly class CancelCommand implements HasDescription, UpdateHandler
{
    public function __construct(private DialogManager $manager) {}

    public static function description(): string
    {
        return __('telegram-bot::dialog.cancel_description');
    }

    public function handle(UpdateContext $context): void
    {
        if (! $this->manager->isActive($context)) {
            return;
        }

        $this->manager->cancel($context);
    }
}

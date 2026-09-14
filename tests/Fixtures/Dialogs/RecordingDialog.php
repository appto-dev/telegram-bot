<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Fixtures\Dialogs;

use Appto\TelegramBot\Dialog\Dialog;

final class RecordingDialog extends Dialog
{
    public function steps(): array
    {
        return ['ask' => RecordingStep::class];
    }
}

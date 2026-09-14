<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Fixtures\Dialogs;

use Appto\TelegramBot\Dialog\DialogContext;
use Appto\TelegramBot\Dialog\Step;
use Appto\TelegramBot\Dialog\StepResult;

/**
 * Asks nothing on enter() and completes the dialog on the first reply it receives — the
 * minimum shape needed to exercise DialogManager's start()/complete()/cancel() event dispatch.
 */
final class RecordingStep implements Step
{
    public function enter(DialogContext $context): void {}

    public function handle(DialogContext $context): StepResult
    {
        return StepResult::complete($context->update->message()?->text);
    }
}

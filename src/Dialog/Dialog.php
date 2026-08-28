<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Dialog;

use Appto\TelegramBot\Update\UpdateContext;
use Appto\TelegramBot\Update\UpdateHandler;

abstract class Dialog implements UpdateHandler
{
    /**
     * A map of the dialog steps, the order is the default order for next().
     *
     * @return array<string, class-string<Step>>
     */
    abstract public function steps(): array;

    /**
     * Called when complete() – the final business logic of the dialog, for example, writing to the database.
     *
     * @param  array<string, mixed>  $answers
     */
    public function onComplete(UpdateContext $context, array $answers): void {}

    /**
     * This is called when cancel() is invoked — for example, when exiting the dialog, such as by using the /cancel command.
     */
    public function onCancel(UpdateContext $context): void {}

    public function handle(UpdateContext $context): void
    {
        app(DialogManager::class)->start($context, $this);

        if ($context->isCallbackQuery()) {
            $context->answerCallbackQuery();
        }
    }

    public function firstStep(): string
    {
        return array_key_first($this->steps());
    }

    public function resolveNameStep(Step $step): string|false
    {
        return array_search(get_class($step), $this->steps(), true);
    }

    public function resolveStep(string $name): Step
    {
        $class = $this->steps()[$name]
            ?? throw new \InvalidArgumentException("Step [{$name}] is not defined in ".static::class);

        return app($class);
    }

    public function nextStepAfter(string $current): ?string
    {
        $names = array_keys($this->steps());
        $index = array_search($current, $names, true);

        return $names[$index + 1] ?? null;
    }

    public function previousStepBefore(string $current): ?string
    {
        $names = array_keys($this->steps());
        $index = array_search($current, $names, true);

        return $names[$index - 1] ?? null;
    }
}

<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Dialog;

final readonly class StepResult
{
    /**
     * @param  array<string, mixed>  $data  данные текущего шага, которые нужно замержить в answers
     * @param  class-string<Dialog>|null  $dialogClass  используется только для SwitchTo
     */
    private function __construct(
        public StepAction $action,
        public string|int|array|null $data = null,
        public ?string $gotoStep = null,
        public ?string $dialogClass = null,
        public ?string $startAtStep = null,
    ) {}

    public static function back(): self
    {
        return new self(StepAction::BACK);
    }

    public static function next(string|int|array|null $data = null): self
    {
        return new self(StepAction::NEXT, data: $data);
    }

    public static function goto(string $stepName, string|int|array|null $data = null): self
    {
        return new self(StepAction::GO_TO, data: $data, gotoStep: $stepName);
    }

    public static function repeat(): self
    {
        return new self(StepAction::REPEAT);
    }

    public static function complete(string|int|array|null $data = null): self
    {
        return new self(StepAction::COMPLETE, data: $data);
    }

    public static function cancel(): self
    {
        return new self(StepAction::CANCEL);
    }

    public static function restart(): self
    {
        return new self(StepAction::RESTART);
    }

    /** @param  class-string<Dialog>  $dialogClass */
    public static function switchTo(string $dialogClass, ?string $startAtStep = null): self
    {
        return new self(StepAction::SWITCH_TO, dialogClass: $dialogClass, startAtStep: $startAtStep);
    }
}

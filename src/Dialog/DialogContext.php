<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Dialog;

use Appto\TelegramBot\Bot\BotIdentity;
use Appto\TelegramBot\Update\UpdateContext;

final readonly class DialogContext
{
    /**
     * @param  array<string, mixed>  $answers  ответы всех пройденных шагов, ключ — имя шага
     */
    public function __construct(
        public BotIdentity $bot,
        public UpdateContext $update,
        public string $step,
        public array $answers,
    ) {}

    public function answer(string $step, mixed $default = null): mixed
    {
        return $this->answers[$step] ?? $default;
    }

    public function hasAnswer(string $step): bool
    {
        return array_key_exists($step, $this->answers);
    }
}

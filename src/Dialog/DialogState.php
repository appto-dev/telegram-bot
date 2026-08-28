<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Dialog;

use DateTimeInterface;
use Spatie\LaravelData\Dto;

final class DialogState extends Dto
{
    /**
     * @param  class-string<Dialog>  $handler
     * @param  array<string, mixed>  $answers
     */
    public function __construct(
        public string $botName,
        public int|string $chatId,
        public int $userId,
        public string $handler,
        public string $step,
        public array $answers = [],
        public ?DateTimeInterface $lastTouchedAt = null,
    ) {}

    public function withStep(string $step): self
    {
        $this->step = $step;

        return $this;
    }

    /** @param  array<string, mixed>  $data */
    public function withMergedAnswers(string $stepName, string|int|array|null $data = null): self
    {
        $this->answers[$stepName] = $data;

        return $this;
    }
}

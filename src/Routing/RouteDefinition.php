<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Routing;

final class RouteDefinition
{
    /**
     * @param  array{scope: 'user'|'chat', max: int, decay: int}|null  $throttle
     */
    public function __construct(
        private readonly string $pattern,
        /** @var callable|string */
        private readonly mixed $handler,
        private ?array $throttle = null,
    ) {}

    public function pattern(): string
    {
        return $this->pattern;
    }

    /**
     * @return callable|string
     */
    public function handler(): mixed
    {
        return $this->handler;
    }

    /**
     * @return array{scope: 'user'|'chat', max: int, decay: int}|null
     */
    public function throttleConfig(): ?array
    {
        return $this->throttle;
    }

    /**
     * Per-user limit for this route (falls back to chat id when there is no "from" user).
     */
    public function throttle(int $maxAttempts = 1, int $decaySeconds = 60): self
    {
        $this->throttle = ['scope' => 'user', 'max' => $maxAttempts, 'decay' => $decaySeconds];

        return $this;
    }

    /**
     * Per-chat limit for this route.
     */
    public function throttlePerChat(int $maxAttempts = 1, int $decaySeconds = 60): self
    {
        $this->throttle = ['scope' => 'chat', 'max' => $maxAttempts, 'decay' => $decaySeconds];

        return $this;
    }
}

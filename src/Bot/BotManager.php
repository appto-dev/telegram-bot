<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Bot;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;

final readonly class BotManager
{
    public function __construct(
        private Container $container,
        private BotRepository $repository,
    ) {}

    public function all(): array
    {
        return $this->repository->all();
    }

    public function findByName(string $name): BotIdentity
    {
        return $this->repository->all()[$name]
            ?? throw new \InvalidArgumentException("Bot [{$name}] is not registered.");
    }

    public function findByWebhookKey(string $key): BotIdentity
    {
        foreach ($this->repository->all() as $identity) {
            if ($identity->webhookKey() === $key) {
                return $identity;
            }
        }

        throw new \InvalidArgumentException("Bot with webhook key [{$key}] is not registered.");
    }

    /**
     * @throws BindingResolutionException
     */
    public function resolve(BotIdentity $identity): Bot
    {
        return $this->container->make($identity->handler, ['identity' => $identity]);
    }
}

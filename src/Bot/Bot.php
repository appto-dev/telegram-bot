<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Bot;

use Appto\TelegramBot\Contracts\CallbackHandler;
use Appto\TelegramBot\Dialog\DialogManager;
use Appto\TelegramBot\Exceptions\RouterException;
use Appto\TelegramBot\Routing\CallbackRouter;
use Appto\TelegramBot\Routing\CommandRouter;
use Appto\TelegramBot\Routing\RouterRegistry;
use Appto\TelegramBot\Routing\TextRouter;
use Appto\TelegramBot\Update\UpdateContext;
use Appto\TelegramBot\Update\UpdateHandler;
use Appto\TelegramBot\Update\UpdateMiddleware;
use Appto\TelegramBot\Update\UpdateRouter;
use Appto\TelegramBot\Update\UpdateType;
use Illuminate\Pipeline\Pipeline;

abstract class Bot
{
    /** @var array<class-string<UpdateMiddleware>> */
    private array $middleware = [];

    /**
     * @throws RouterException
     */
    public function __construct(
        private readonly BotIdentity $identity,
        private readonly RouterRegistry $router,
    ) {
        $this->router()->register([
            TextRouter::class,
            CallbackRouter::class,
            CommandRouter::class,
            UpdateRouter::class,
        ]);

        $this->boot();
    }

    /**
     * @param  callable|class-string<UpdateHandler>  $handler
     */
    protected function onText(string $text, callable|string $handler): void
    {
        $this->router()->get(TextRouter::key())->add($text, $handler);
    }

    /**
     * @param  callable|class-string<CallbackHandler|UpdateHandler>  $handler
     */
    protected function onCallback(string $pattern, callable|string $handler): void
    {
        $this->router()->get(CallbackRouter::key())->add($pattern, $handler);
    }

    /**
     * @param  callable|class-string<UpdateHandler>  $handler
     */
    protected function onCommand(string $name, callable|string $handler): void
    {
        $this->router()->get(CommandRouter::key())->add($name, $handler);
    }

    protected function onUpdate(UpdateType|array $type, callable|string|null $handler = null): void
    {
        if (is_array($type)) {
            foreach ($type as $updateType => $handler) {
                if ($updateType instanceof UpdateType) {
                    $this->onUpdate($updateType, $handler);
                }
            }

            return;
        }

        $this->router()->get(UpdateRouter::key())->add($type->value, $handler);
    }

    /**
     * @throws RouterException
     */
    final public function dispatch(UpdateContext $context): void
    {
        app(Pipeline::class)
            ->send($context)
            ->through($this->middleware)
            ->then(function (UpdateContext $context): void {
                if (app(DialogManager::class)->handle($context)) {
                    return;
                }

                if ($this->router()->dispatch($context)) {
                    return;
                }

                $this->fallback($context);
            });
    }

    public function router(): RouterRegistry
    {
        return $this->router;
    }

    protected function middleware(array $middlewareClasses): void
    {
        $this->middleware = $middlewareClasses;
    }

    protected function fallback(UpdateContext $context): void {}

    protected function identity(): BotIdentity
    {
        return $this->identity;
    }

    abstract protected function boot(): void;
}

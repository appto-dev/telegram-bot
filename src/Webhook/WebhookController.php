<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Webhook;

use Appto\TelegramBot\Bot\BotIdentity;
use Appto\TelegramBot\Bot\BotManager;
use Appto\TelegramBot\Type\Update;
use Appto\TelegramBot\Update\Deduplicator;
use Appto\TelegramBot\Update\UpdateContext;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final readonly class WebhookController
{
    public function __construct(
        private BotManager $manager,
        private Deduplicator $deduplicator,
    ) {}

    public function __invoke(Request $request, string $botId): Response
    {
        /** @var BotIdentity $identity */
        $identity = $request->attributes->get('telegramBotIdentity');

        $update = Update::from($request);
        $context = new UpdateContext($identity, $update);

        if (! $this->deduplicator->markAsProcessed((string) $update->update_id, $botId)) {
            return response()->noContent();
        }

        $this->manager->resolve($identity)->dispatch($context);

        return response()->noContent();
    }
}

<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Update;

use Appto\TelegramBot\Bot\BotIdentity;
use Appto\TelegramBot\Client\Enums\MessageEntityType;
use Appto\TelegramBot\Client\TelegramClient;
use Appto\TelegramBot\Client\TelegramClientFactory;
use Appto\TelegramBot\Type\Message;
use Appto\TelegramBot\Type\MessageEntity;
use Appto\TelegramBot\Type\Update;

final class UpdateContext implements CanReply
{
    use InteractsWithReplies;

    public function __construct(
        public BotIdentity $bot,
        private readonly Update $update,
    ) {}

    public function client(): TelegramClient
    {
        /** @var TelegramClientFactory $factory */
        $factory = app(TelegramClientFactory::class);

        return $factory->make($this->bot);
    }

    public function update(): Update
    {
        return $this->update;
    }

    public function message(): ?Message
    {
        $type = UpdateType::detect($this->update);

        return $type->payloadClass() === Message::class
            ? $this->update()->{$type->value}
            : null;
    }

    public function command(): ?string
    {
        $message = $this->message();
        if (! $message || ! $message->entities) {
            return null;
        }

        /** @var MessageEntity $entity */
        $entity = array_filter($message->entities,
            fn (MessageEntity $entity) => $entity->type === MessageEntityType::BOT_COMMAND->value
        );

        if (! $entity) {
            return null;
        }

        $command = mb_substr($message->text, $entity[0]->offset + 1, $entity[0]->length);

        return strtok($command, '@');
    }

    public function chatId(): int|string|null
    {
        $type = UpdateType::detect($this->update);
        $update = $this->update()->{$type->value};

        return $update->chat?->id ?? $update->message?->chat?->id;
    }

    public function userId(): int|string|null
    {
        $type = UpdateType::detect($this->update);
        $update = $this->update()->{$type->value};

        return $update->from?->id;
    }

    public function isCommand(): bool
    {
        return $this->command() !== null;
    }

    public function isCallbackQuery(): bool
    {
        return $this->update()->callback_query !== null;
    }

    public function isBusinessChat(): bool
    {
        return $this->message()?->business_connection_id !== null;
    }

    public function isPrivate(): bool
    {
        $type = UpdateType::detect($this->update);
        $update = $this->update()->{$type->value};

        return ($update->chat?->type ?? $update->message?->chat?->type) === 'private';
    }
}

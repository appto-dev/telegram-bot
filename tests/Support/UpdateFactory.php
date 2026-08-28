<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Support;

use Appto\TelegramBot\Bot\BotIdentity;
use Appto\TelegramBot\Type\Update;
use Appto\TelegramBot\Update\UpdateContext;

/**
 * Builds minimal-but-realistic Telegram Bot API payloads for tests, and wraps them in the
 * UpdateContext objects the framework's routers/handlers actually operate on.
 */
final class UpdateFactory
{
    public static function bot(string $id = 'test-bot'): BotIdentity
    {
        return BotIdentity::from([
            'id' => $id,
            'token' => '123456:TEST-TOKEN',
            'webhook_secret' => 'secret',
            'handler' => \stdClass::class,
        ]);
    }

    public static function context(array $updatePayload, ?BotIdentity $bot = null): UpdateContext
    {
        return new UpdateContext($bot ?? self::bot(), Update::from($updatePayload));
    }

    /**
     * A plain text message (no command entity).
     */
    public static function textMessage(string $text, int $chatId = 111, int $userId = 222): array
    {
        return [
            'update_id' => random_int(1, PHP_INT_MAX),
            'message' => [
                'message_id' => random_int(1, PHP_INT_MAX),
                'date' => time(),
                'chat' => ['id' => $chatId, 'type' => 'private'],
                'from' => ['id' => $userId, 'is_bot' => false, 'first_name' => 'Test'],
                'text' => $text,
            ],
        ];
    }

    /**
     * A message with a caption but no text (e.g. a photo) — TextRouter falls back to this.
     */
    public static function captionedMessage(string $caption, int $chatId = 111, int $userId = 222): array
    {
        return [
            'update_id' => random_int(1, PHP_INT_MAX),
            'message' => [
                'message_id' => random_int(1, PHP_INT_MAX),
                'date' => time(),
                'chat' => ['id' => $chatId, 'type' => 'private'],
                'from' => ['id' => $userId, 'is_bot' => false, 'first_name' => 'Test'],
                'caption' => $caption,
            ],
        ];
    }

    /**
     * A "/command" (optionally "/command@botname") message, with the bot_command entity Telegram
     * itself would attach — UpdateContext::command() relies on this entity, not on string parsing.
     */
    public static function command(string $command, ?string $botUsername = null, int $chatId = 111, int $userId = 222): array
    {
        $text = '/'.ltrim($command, '/').($botUsername ? '@'.$botUsername : '');
        $length = mb_strlen('/'.ltrim($command, '/').($botUsername ? '@'.$botUsername : ''));

        return [
            'update_id' => random_int(1, PHP_INT_MAX),
            'message' => [
                'message_id' => random_int(1, PHP_INT_MAX),
                'date' => time(),
                'chat' => ['id' => $chatId, 'type' => 'private'],
                'from' => ['id' => $userId, 'is_bot' => false, 'first_name' => 'Test'],
                'text' => $text,
                'entities' => [
                    ['type' => 'bot_command', 'offset' => 0, 'length' => $length],
                ],
            ],
        ];
    }

    public static function callbackQuery(string $data, int $chatId = 111, int $userId = 222): array
    {
        return [
            'update_id' => random_int(1, PHP_INT_MAX),
            'callback_query' => [
                'id' => (string) random_int(1, PHP_INT_MAX),
                'from' => ['id' => $userId, 'is_bot' => false, 'first_name' => 'Test'],
                'chat_instance' => 'chat-instance',
                'data' => $data,
                'message' => [
                    'message_id' => random_int(1, PHP_INT_MAX),
                    'date' => time(),
                    'chat' => ['id' => $chatId, 'type' => 'private'],
                ],
            ],
        ];
    }
}

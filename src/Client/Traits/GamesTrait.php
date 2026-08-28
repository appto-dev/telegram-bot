<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client\Traits;

use Appto\TelegramBot\Type\GameHighScore;
use Appto\TelegramBot\Type\InlineKeyboardMarkup;
use Appto\TelegramBot\Type\Message;
use Appto\TelegramBot\Type\ReplyParameters;

trait GamesTrait
{
    public function sendGame(
        int|string $chat_id,
        string $game_short_name,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?ReplyParameters $reply_parameters = null,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendGame', [
            'chat_id' => $chat_id,
            'game_short_name' => $game_short_name,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function setGameScore(
        int $user_id,
        int $score,
        ?bool $force = null,
        ?bool $disable_edit_message = null,
        ?int $chat_id = null,
        ?int $message_id = null,
        ?string $inline_message_id = null,
    ): Message|bool {
        $response = $this->call('setGameScore', [
            'user_id' => $user_id,
            'score' => $score,
            'force' => $force,
            'disable_edit_message' => $disable_edit_message,
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'inline_message_id' => $inline_message_id,
        ]);

        return is_array($response) ? Message::from($response) : $response;
    }

    public function getGameHighScores(
        int $user_id,
        ?int $chat_id = null,
        ?int $message_id = null,
        ?string $inline_message_id = null,
    ): array {
        return GameHighScore::collect(
            $this->call('getGameHighScores', [
                'user_id' => $user_id,
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'inline_message_id' => $inline_message_id,
            ])
        );
    }
}

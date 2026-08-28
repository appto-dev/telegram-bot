<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client\Traits;

use Appto\TelegramBot\Type\EphemeralMessageParameters;
use Appto\TelegramBot\Type\ForceReply;
use Appto\TelegramBot\Type\InlineKeyboardMarkup;
use Appto\TelegramBot\Type\InputRichMessage;
use Appto\TelegramBot\Type\Message;
use Appto\TelegramBot\Type\ReplyKeyboardMarkup;
use Appto\TelegramBot\Type\ReplyKeyboardRemove;
use Appto\TelegramBot\Type\ReplyParameters;
use Appto\TelegramBot\Type\SuggestedPostParameters;

trait RichMessagesTrait
{
    public function sendRichMessage(
        int|string $chat_id,
        InputRichMessage $rich_message,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?EphemeralMessageParameters $ephemeral_message_parameters = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null,
    ): Message {
        return Message::from(
            $this->call('sendRichMessage', [
                'chat_id' => $chat_id,
                'rich_message' => $rich_message,
                'business_connection_id' => $business_connection_id,
                'message_thread_id' => $message_thread_id,
                'direct_messages_topic_id' => $direct_messages_topic_id,
                'ephemeral_message_parameters' => $ephemeral_message_parameters,
                'disable_notification' => $disable_notification,
                'protect_content' => $protect_content,
                'allow_paid_broadcast' => $allow_paid_broadcast,
                'message_effect_id' => $message_effect_id,
                'suggested_post_parameters' => $suggested_post_parameters,
                'reply_parameters' => $reply_parameters,
                'reply_markup' => $reply_markup,
            ])
        );
    }

    public function sendRichMessageDraft(
        int $chat_id,
        int $draft_id,
        InputRichMessage $rich_message,
        ?int $message_thread_id = null,
        ?bool $can_stop = null,
        ?bool $keep_on_stop = null,
    ): true {
        return $this->call('sendRichMessageDraft', [
            'chat_id' => $chat_id,
            'draft_id' => $draft_id,
            'rich_message' => $rich_message,
            'message_thread_id' => $message_thread_id,
            'can_stop' => $can_stop,
            'keep_on_stop' => $keep_on_stop,
        ]);
    }
}

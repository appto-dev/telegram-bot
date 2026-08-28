<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client\Traits;

use Appto\TelegramBot\Type\InlineKeyboardMarkup;
use Appto\TelegramBot\Type\InputChecklist;
use Appto\TelegramBot\Type\InputMedia;
use Appto\TelegramBot\Type\InputRichMessage;
use Appto\TelegramBot\Type\LinkPreviewOptions;
use Appto\TelegramBot\Type\Message;
use Appto\TelegramBot\Type\Poll;

trait UpdatingMessagesTrait
{
    public function editMessageText(
        ?string $business_connection_id = null,
        int|string|null $chat_id = null,
        ?int $message_id = null,
        ?string $inline_message_id = null,
        ?string $text = null,
        ?string $parse_mode = null,
        ?array $entities = null,
        ?LinkPreviewOptions $link_preview_options = null,
        ?InputRichMessage $rich_message = null,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): true|Message {
        $response = $this->call('editMessageText', [
            'business_connection_id' => $business_connection_id,
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'inline_message_id' => $inline_message_id,
            'text' => $text,
            'parse_mode' => $parse_mode,
            'entities' => $entities,
            'link_preview_options' => $link_preview_options,
            'rich_message' => $rich_message,
            'reply_markup' => $reply_markup,
        ]);

        return is_array($response) ? Message::from($response) : $response;
    }

    public function editMessageCaption(
        ?string $business_connection_id = null,
        int|string|null $chat_id = null,
        ?int $message_id = null,
        ?string $inline_message_id = null,
        ?string $caption = null,
        ?string $parse_mode = null,
        ?array $caption_entities = null,
        ?bool $show_caption_above_media = null,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): true|Message {
        $response = $this->call('editMessageCaption', [
            'business_connection_id' => $business_connection_id,
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'inline_message_id' => $inline_message_id,
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'caption_entities' => $caption_entities,
            'show_caption_above_media' => $show_caption_above_media,
            'reply_markup' => $reply_markup,
        ]);

        return is_array($response) ? Message::from($response) : $response;
    }

    public function editMessageMedia(
        InputMedia $media,
        ?string $business_connection_id = null,
        int|string|null $chat_id = null,
        ?int $message_id = null,
        ?string $inline_message_id = null,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): true|Message {
        $response = $this->call('editMessageMedia', [
            'media' => $media,
            'business_connection_id' => $business_connection_id,
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'inline_message_id' => $inline_message_id,
            'reply_markup' => $reply_markup,
        ]);

        return is_array($response) ? Message::from($response) : $response;
    }

    public function editMessageLiveLocation(
        float $latitude,
        float $longitude,
        ?string $business_connection_id = null,
        int|string|null $chat_id = null,
        ?int $message_id = null,
        ?string $inline_message_id = null,
        ?int $live_period = null,
        ?float $horizontal_accuracy = null,
        ?int $heading = null,
        ?int $proximity_alert_radius = null,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): true|Message {
        $response = $this->call('editMessageLiveLocation', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'business_connection_id' => $business_connection_id,
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'inline_message_id' => $inline_message_id,
            'live_period' => $live_period,
            'horizontal_accuracy' => $horizontal_accuracy,
            'heading' => $heading,
            'proximity_alert_radius' => $proximity_alert_radius,
            'reply_markup' => $reply_markup,
        ]);

        return is_array($response) ? Message::from($response) : $response;
    }

    public function stopMessageLiveLocation(
        ?string $business_connection_id = null,
        int|string|null $chat_id = null,
        ?int $message_id = null,
        ?string $inline_message_id = null,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): true|Message {
        $response = $this->call('stopMessageLiveLocation', [
            'business_connection_id' => $business_connection_id,
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'inline_message_id' => $inline_message_id,
            'reply_markup' => $reply_markup,
        ]);

        return is_array($response) ? Message::from($response) : $response;
    }

    public function editMessageChecklist(
        string $business_connection_id,
        int|string $chat_id,
        int $message_id,
        InputChecklist $checklist,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): Message {
        return Message::from(
            $this->call('editMessageChecklist', [
                'business_connection_id' => $business_connection_id,
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'checklist' => $checklist,
                'reply_markup' => $reply_markup,
            ])
        );
    }

    public function editMessageReplyMarkup(
        ?string $business_connection_id = null,
        int|string|null $chat_id = null,
        ?int $message_id = null,
        ?string $inline_message_id = null,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): true|Message {
        $response = $this->call('editMessageReplyMarkup', [
            'business_connection_id' => $business_connection_id,
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'inline_message_id' => $inline_message_id,
            'reply_markup' => $reply_markup,
        ]);

        return is_array($response) ? Message::from($response) : $response;
    }

    public function stopPoll(
        int|string $chat_id,
        int $message_id,
        ?string $business_connection_id = null,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): Poll {
        return Poll::from(
            $this->call('stopPoll', [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'business_connection_id' => $business_connection_id,
                'reply_markup' => $reply_markup,
            ])
        );
    }

    public function editEphemeralMessageText(
        int|string $chat_id,
        int $receiver_user_id,
        int $ephemeral_message_id,
        ?string $text = null,
        ?string $parse_mode = null,
        ?array $entities = null,
        ?InputRichMessage $rich_message = null,
        ?LinkPreviewOptions $link_preview_options = null,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): true {
        return $this->call('editEphemeralMessageText', [
            'chat_id' => $chat_id,
            'receiver_user_id' => $receiver_user_id,
            'ephemeral_message_id' => $ephemeral_message_id,
            'text' => $text,
            'parse_mode' => $parse_mode,
            'entities' => $entities,
            'rich_message' => $rich_message,
            'link_preview_options' => $link_preview_options,
            'reply_markup' => $reply_markup,
        ]);
    }

    public function editEphemeralMessageMedia(
        int|string $chat_id,
        int $receiver_user_id,
        int $ephemeral_message_id,
        InputMedia $media,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): true {
        return $this->call('editEphemeralMessageMedia', [
            'chat_id' => $chat_id,
            'receiver_user_id' => $receiver_user_id,
            'ephemeral_message_id' => $ephemeral_message_id,
            'media' => $media,
            'reply_markup' => $reply_markup,
        ]);
    }

    public function editEphemeralMessageCaption(
        int|string $chat_id,
        int $receiver_user_id,
        int $ephemeral_message_id,
        ?string $caption = null,
        ?string $parse_mode = null,
        ?array $caption_entities = null,
        ?bool $show_caption_above_media = null,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): true {
        return $this->call('editEphemeralMessageCaption', [
            'chat_id' => $chat_id,
            'receiver_user_id' => $receiver_user_id,
            'ephemeral_message_id' => $ephemeral_message_id,
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'caption_entities' => $caption_entities,
            'show_caption_above_media' => $show_caption_above_media,
            'reply_markup' => $reply_markup,
        ]);
    }

    public function editEphemeralMessageReplyMarkup(
        int|string $chat_id,
        int $receiver_user_id,
        int $ephemeral_message_id,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): true {
        return $this->call('editEphemeralMessageReplyMarkup', [
            'chat_id' => $chat_id,
            'receiver_user_id' => $receiver_user_id,
            'ephemeral_message_id' => $ephemeral_message_id,
            'reply_markup' => $reply_markup,
        ]);
    }

    public function approveSuggestedPost(int $chat_id, int $message_id, ?int $send_date = null): true
    {
        return $this->call('approveSuggestedPost', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'send_date' => $send_date,
        ]);
    }

    public function declineSuggestedPost(int $chat_id, int $message_id, ?string $comment = null): true
    {
        return $this->call('declineSuggestedPost', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'comment' => $comment,
        ]);
    }

    public function deleteMessage(int|string $chat_id, int $message_id): true
    {
        return $this->call('deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ]);
    }

    public function deleteMessages(int|string $chat_id, array $message_ids): true
    {
        return $this->call('deleteMessages', [
            'chat_id' => $chat_id,
            'message_ids' => $message_ids,
        ]);
    }

    public function deleteEphemeralMessage(
        int|string $chat_id,
        int $receiver_user_id,
        int $ephemeral_message_id,
    ): true {
        return $this->call('deleteEphemeralMessage', [
            'chat_id' => $chat_id,
            'receiver_user_id' => $receiver_user_id,
            'ephemeral_message_id' => $ephemeral_message_id,
        ]);
    }

    public function deleteMessageReaction(
        int|string $chat_id,
        int $message_id,
        ?int $user_id = null,
        ?int $actor_chat_id = null,
    ): true {
        return $this->call('deleteMessageReaction', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'user_id' => $user_id,
            'actor_chat_id' => $actor_chat_id,
        ]);
    }

    public function deleteAllMessageReactions(
        int|string $chat_id,
        ?int $user_id = null,
        ?int $actor_chat_id = null,
    ): true {
        return $this->call('deleteAllMessageReactions', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
            'actor_chat_id' => $actor_chat_id,
        ]);
    }
}

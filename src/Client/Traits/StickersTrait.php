<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client\Traits;

use Appto\TelegramBot\Type\EphemeralMessageParameters;
use Appto\TelegramBot\Type\File;
use Appto\TelegramBot\Type\ForceReply;
use Appto\TelegramBot\Type\InlineKeyboardMarkup;
use Appto\TelegramBot\Type\InputFile;
use Appto\TelegramBot\Type\InputSticker;
use Appto\TelegramBot\Type\MaskPosition;
use Appto\TelegramBot\Type\Message;
use Appto\TelegramBot\Type\ReplyKeyboardMarkup;
use Appto\TelegramBot\Type\ReplyKeyboardRemove;
use Appto\TelegramBot\Type\ReplyParameters;
use Appto\TelegramBot\Type\Sticker;
use Appto\TelegramBot\Type\StickerSet;
use Appto\TelegramBot\Type\SuggestedPostParameters;

trait StickersTrait
{
    public function sendSticker(
        int|string $chat_id,
        InputFile|string $sticker,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?EphemeralMessageParameters $ephemeral_message_parameters = null,
        ?string $emoji = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null,
    ): Message {
        return Message::from(
            $this->call('sendSticker', [
                'chat_id' => $chat_id,
                'sticker' => $sticker,
                'business_connection_id' => $business_connection_id,
                'message_thread_id' => $message_thread_id,
                'direct_messages_topic_id' => $direct_messages_topic_id,
                'ephemeral_message_parameters' => $ephemeral_message_parameters,
                'emoji' => $emoji,
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

    public function getStickerSet(string $name): StickerSet
    {
        return StickerSet::from($this->call('getStickerSet', ['name' => $name]));
    }

    public function getCustomEmojiStickers(array $custom_emoji_ids): array
    {
        return Sticker::collect(
            $this->call('getCustomEmojiStickers', [
                'custom_emoji_ids' => $custom_emoji_ids,
            ])
        );
    }

    public function uploadStickerFile(int $user_id, InputFile $sticker, string $sticker_format): File
    {
        return File::from(
            $this->call('uploadStickerFile', [
                'user_id' => $user_id,
                'sticker' => $sticker,
                'sticker_format' => $sticker_format,
            ])
        );
    }

    public function createNewStickerSet(
        int $user_id,
        string $name,
        string $title,
        array $stickers,
        ?string $sticker_type = null,
        ?bool $needs_repainting = null,
    ): true {
        return $this->call('createNewStickerSet', [
            'user_id' => $user_id,
            'name' => $name,
            'title' => $title,
            'stickers' => $stickers,
            'sticker_type' => $sticker_type,
            'needs_repainting' => $needs_repainting,
        ]);
    }

    public function addStickerToSet(int $user_id, string $name, InputSticker $sticker): true
    {
        return $this->call('addStickerToSet', [
            'user_id' => $user_id,
            'name' => $name,
            'sticker' => $sticker,
        ]);
    }

    public function setStickerPositionInSet(string $sticker, int $position): true
    {
        return $this->call('setStickerPositionInSet', [
            'sticker' => $sticker,
            'position' => $position,
        ]);
    }

    public function deleteStickerFromSet(string $sticker): true
    {
        return $this->call('deleteStickerFromSet', [
            'sticker' => $sticker,
        ]);
    }

    public function replaceStickerInSet(int $user_id, string $name, string $old_sticker, InputSticker $sticker): true
    {
        return $this->call('replaceStickerInSet', [
            'user_id' => $user_id,
            'name' => $name,
            'old_sticker' => $old_sticker,
            'sticker' => $sticker,
        ]);
    }

    public function setStickerEmojiList(string $sticker, array $emoji_list): true
    {
        return $this->call('setStickerEmojiList', [
            'sticker' => $sticker,
            'emoji_list' => $emoji_list,
        ]);
    }

    public function setStickerKeywords(string $sticker, ?array $keywords = null): true
    {
        return $this->call('setStickerKeywords', [
            'sticker' => $sticker,
            'keywords' => $keywords,
        ]);
    }

    public function setStickerMaskPosition(string $sticker, ?MaskPosition $mask_position = null): true
    {
        return $this->call('setStickerMaskPosition', [
            'sticker' => $sticker,
            'mask_position' => $mask_position,
        ]);
    }

    public function setStickerSetTitle(string $name, string $title): true
    {
        return $this->call('setStickerSetTitle', [
            'name' => $name,
            'title' => $title,
        ]);
    }

    public function setStickerSetThumbnail(
        string $name,
        int $user_id,
        string $format,
        string|InputFile|null $thumbnail = null,
    ): true {
        return $this->call('setStickerSetThumbnail', [
            'name' => $name,
            'user_id' => $user_id,
            'format' => $format,
            'thumbnail' => $thumbnail,
        ]);
    }

    public function setCustomEmojiStickerSetThumbnail(string $name, ?string $custom_emoji_id = null): true
    {
        return $this->call('setCustomEmojiStickerSetThumbnail', [
            'name' => $name,
            'custom_emoji_id' => $custom_emoji_id,
        ]);
    }

    public function deleteStickerSet(string $name): true
    {
        return $this->call('deleteStickerSet', [
            'name' => $name,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client\Traits;

use Appto\TelegramBot\Type\AcceptedGiftTypes;
use Appto\TelegramBot\Type\BotAccessSettings;
use Appto\TelegramBot\Type\BotCommand;
use Appto\TelegramBot\Type\BotCommandScope;
use Appto\TelegramBot\Type\BotDescription;
use Appto\TelegramBot\Type\BotName;
use Appto\TelegramBot\Type\BotShortDescription;
use Appto\TelegramBot\Type\BusinessConnection;
use Appto\TelegramBot\Type\ChatAdministratorRights;
use Appto\TelegramBot\Type\ChatFullInfo;
use Appto\TelegramBot\Type\ChatInviteLink;
use Appto\TelegramBot\Type\ChatMember;
use Appto\TelegramBot\Type\ChatMemberAdministrator;
use Appto\TelegramBot\Type\ChatMemberBanned;
use Appto\TelegramBot\Type\ChatMemberLeft;
use Appto\TelegramBot\Type\ChatMemberMember;
use Appto\TelegramBot\Type\ChatMemberOwner;
use Appto\TelegramBot\Type\ChatMemberRestricted;
use Appto\TelegramBot\Type\ChatPermissions;
use Appto\TelegramBot\Type\EphemeralMessageParameters;
use Appto\TelegramBot\Type\File;
use Appto\TelegramBot\Type\ForceReply;
use Appto\TelegramBot\Type\ForumTopic;
use Appto\TelegramBot\Type\Gifts;
use Appto\TelegramBot\Type\InlineKeyboardMarkup;
use Appto\TelegramBot\Type\InlineQueryResult;
use Appto\TelegramBot\Type\InputChecklist;
use Appto\TelegramBot\Type\InputFile;
use Appto\TelegramBot\Type\InputPollMedia;
use Appto\TelegramBot\Type\InputProfilePhoto;
use Appto\TelegramBot\Type\InputStoryContent;
use Appto\TelegramBot\Type\KeyboardButton;
use Appto\TelegramBot\Type\LinkPreviewOptions;
use Appto\TelegramBot\Type\MenuButton;
use Appto\TelegramBot\Type\MenuButtonCommands;
use Appto\TelegramBot\Type\MenuButtonDefault;
use Appto\TelegramBot\Type\MenuButtonWebApp;
use Appto\TelegramBot\Type\Message;
use Appto\TelegramBot\Type\MessageId;
use Appto\TelegramBot\Type\OwnedGifts;
use Appto\TelegramBot\Type\PreparedInlineMessage;
use Appto\TelegramBot\Type\PreparedKeyboardButton;
use Appto\TelegramBot\Type\ReplyKeyboardMarkup;
use Appto\TelegramBot\Type\ReplyKeyboardRemove;
use Appto\TelegramBot\Type\ReplyParameters;
use Appto\TelegramBot\Type\SentGuestMessage;
use Appto\TelegramBot\Type\SentWebAppMessage;
use Appto\TelegramBot\Type\StarAmount;
use Appto\TelegramBot\Type\Sticker;
use Appto\TelegramBot\Type\Story;
use Appto\TelegramBot\Type\SuggestedPostParameters;
use Appto\TelegramBot\Type\User;
use Appto\TelegramBot\Type\UserChatBoosts;
use Appto\TelegramBot\Type\UserProfileAudios;
use Appto\TelegramBot\Type\UserProfilePhotos;
use Spatie\LaravelData\Casts\Uncastable;

trait AvailableMethodsTrait
{
    public function getMe(): User
    {
        return User::from($this->call('getMe'));
    }

    public function logOut(): true
    {
        return $this->call('logOut');
    }

    public function close(): true
    {
        return $this->call('close');
    }

    public function sendMessage(
        int|string $chat_id,
        string $text,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?EphemeralMessageParameters $ephemeral_message_parameters = null,
        ?string $parse_mode = null,
        ?array $entities = null,
        ?LinkPreviewOptions $link_preview_options = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'ephemeral_message_parameters' => $ephemeral_message_parameters,
            'parse_mode' => $parse_mode,
            'entities' => $entities,
            'link_preview_options' => $link_preview_options,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'suggested_post_parameters' => $suggested_post_parameters,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function forwardMessage(
        int|string $chat_id,
        int|string $from_chat_id,
        int $message_id,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?int $video_start_timestamp = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
    ): Message {
        return Message::from($this->call('forwardMessage', [
            'chat_id' => $chat_id,
            'from_chat_id' => $from_chat_id,
            'message_id' => $message_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'video_start_timestamp' => $video_start_timestamp,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'message_effect_id' => $message_effect_id,
            'suggested_post_parameters' => $suggested_post_parameters,
        ]));
    }

    public function forwardMessages(
        int|string $chat_id,
        int|string $from_chat_id,
        array $message_ids,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
    ): array {
        return MessageId::collect(
            $this->call('forwardMessages', [
                'chat_id' => $chat_id,
                'from_chat_id' => $from_chat_id,
                'message_ids' => $message_ids,
                'message_thread_id' => $message_thread_id,
                'direct_messages_topic_id' => $direct_messages_topic_id,
                'disable_notification' => $disable_notification,
                'protect_content' => $protect_content,
            ])
        );
    }

    public function copyMessage(
        int|string $chat_id,
        int|string $from_chat_id,
        int $message_id,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?int $video_start_timestamp = null,
        ?string $caption = null,
        ?string $parse_mode = null,
        ?array $caption_entities = null,
        ?bool $show_caption_above_media = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        ReplyKeyboardRemove|ForceReply|InlineKeyboardMarkup|ReplyKeyboardMarkup|null $reply_markup = null,
    ): MessageId {
        return MessageId::from($this->call('copyMessage', [
            'chat_id' => $chat_id,
            'from_chat_id' => $from_chat_id,
            'message_id' => $message_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'video_start_timestamp' => $video_start_timestamp,
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'caption_entities' => $caption_entities,
            'show_caption_above_media' => $show_caption_above_media,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'suggested_post_parameters' => $suggested_post_parameters,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function copyMessages(
        int|string $chat_id,
        int|string $from_chat_id,
        array $message_ids,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $remove_caption = null,
    ): array {
        return MessageId::collect(
            $this->call('copyMessages', [
                'chat_id' => $chat_id,
                'from_chat_id' => $from_chat_id,
                'message_ids' => $message_ids,
                'message_thread_id' => $message_thread_id,
                'direct_messages_topic_id' => $direct_messages_topic_id,
                'disable_notification' => $disable_notification,
                'protect_content' => $protect_content,
                'remove_caption' => $remove_caption,
            ])
        );
    }

    public function sendPhoto(
        int|string $chat_id,
        InputFile|string $photo,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?EphemeralMessageParameters $ephemeral_message_parameters = null,
        ?string $caption = null,
        ?string $parse_mode = null,
        ?array $caption_entities = null,
        ?bool $show_caption_above_media = null,
        ?bool $has_spoiler = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendPhoto', [
            'chat_id' => $chat_id,
            'photo' => $photo,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'ephemeral_message_parameters' => $ephemeral_message_parameters,
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'caption_entities' => $caption_entities,
            'show_caption_above_media' => $show_caption_above_media,
            'has_spoiler' => $has_spoiler,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'suggested_post_parameters' => $suggested_post_parameters,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function sendLivePhoto(
        int|string $chat_id,
        InputFile|string $live_photo,
        InputFile|string $photo,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?EphemeralMessageParameters $ephemeral_message_parameters = null,
        ?string $caption = null,
        ?string $parse_mode = null,
        ?array $caption_entities = null,
        ?bool $show_caption_above_media = null,
        ?bool $has_spoiler = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendLivePhoto', [
            'chat_id' => $chat_id,
            'live_photo' => $live_photo,
            'photo' => $photo,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'ephemeral_message_parameters' => $ephemeral_message_parameters,
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'caption_entities' => $caption_entities,
            'show_caption_above_media' => $show_caption_above_media,
            'has_spoiler' => $has_spoiler,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'suggested_post_parameters' => $suggested_post_parameters,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function sendAudio(
        int|string $chat_id,
        InputFile|string $audio,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?EphemeralMessageParameters $ephemeral_message_parameters = null,
        ?string $caption = null,
        ?string $parse_mode = null,
        ?array $caption_entities = null,
        ?int $duration = null,
        ?string $performer = null,
        ?string $title = null,
        InputFile|string|null $thumbnail = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendAudio', [
            'chat_id' => $chat_id,
            'audio' => $audio,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'ephemeral_message_parameters' => $ephemeral_message_parameters,
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'caption_entities' => $caption_entities,
            'duration' => $duration,
            'performer' => $performer,
            'title' => $title,
            'thumbnail' => $thumbnail,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'suggested_post_parameters' => $suggested_post_parameters,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function sendDocument(
        int|string $chat_id,
        InputFile|string $document,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?EphemeralMessageParameters $ephemeral_message_parameters = null,
        InputFile|string|null $thumbnail = null,
        ?string $caption = null,
        ?string $parse_mode = null,
        ?array $caption_entities = null,
        ?bool $disable_content_type_detection = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendDocument', [
            'chat_id' => $chat_id,
            'document' => $document,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'ephemeral_message_parameters' => $ephemeral_message_parameters,
            'thumbnail' => $thumbnail,
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'caption_entities' => $caption_entities,
            'disable_content_type_detection' => $disable_content_type_detection,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'suggested_post_parameters' => $suggested_post_parameters,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function sendVideo(
        int|string $chat_id,
        InputFile|string $video,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?EphemeralMessageParameters $ephemeral_message_parameters = null,
        ?int $duration = null,
        ?int $width = null,
        ?int $height = null,
        InputFile|string|null $thumbnail = null,
        InputFile|string|null $cover = null,
        ?int $start_timestamp = null,
        ?string $caption = null,
        ?string $parse_mode = null,
        ?array $caption_entities = null,
        ?bool $show_caption_above_media = null,
        ?bool $has_spoiler = null,
        ?bool $supports_streaming = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendVideo', [
            'chat_id' => $chat_id,
            'video' => $video,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'ephemeral_message_parameters' => $ephemeral_message_parameters,
            'duration' => $duration,
            'width' => $width,
            'height' => $height,
            'thumbnail' => $thumbnail,
            'cover' => $cover,
            'start_timestamp' => $start_timestamp,
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'caption_entities' => $caption_entities,
            'show_caption_above_media' => $show_caption_above_media,
            'has_spoiler' => $has_spoiler,
            'supports_streaming' => $supports_streaming,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'suggested_post_parameters' => $suggested_post_parameters,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function sendAnimation(
        int|string $chat_id,
        InputFile|string $animation,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?EphemeralMessageParameters $ephemeral_message_parameters = null,
        ?int $duration = null,
        ?int $width = null,
        ?int $height = null,
        InputFile|string|null $thumbnail = null,
        ?string $caption = null,
        ?string $parse_mode = null,
        ?array $caption_entities = null,
        ?bool $show_caption_above_media = null,
        ?bool $has_spoiler = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendAnimation', [
            'chat_id' => $chat_id,
            'animation' => $animation,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'ephemeral_message_parameters' => $ephemeral_message_parameters,
            'duration' => $duration,
            'width' => $width,
            'height' => $height,
            'thumbnail' => $thumbnail,
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'caption_entities' => $caption_entities,
            'show_caption_above_media' => $show_caption_above_media,
            'has_spoiler' => $has_spoiler,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'suggested_post_parameters' => $suggested_post_parameters,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function sendVoice(
        int|string $chat_id,
        InputFile|string $voice,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?EphemeralMessageParameters $ephemeral_message_parameters = null,
        ?string $caption = null,
        ?string $parse_mode = null,
        ?array $caption_entities = null,
        ?int $duration = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendVoice', [
            'chat_id' => $chat_id,
            'voice' => $voice,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'ephemeral_message_parameters' => $ephemeral_message_parameters,
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'caption_entities' => $caption_entities,
            'duration' => $duration,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'suggested_post_parameters' => $suggested_post_parameters,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function sendVideoNote(
        int|string $chat_id,
        InputFile|string $video_note,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?EphemeralMessageParameters $ephemeral_message_parameters = null,
        ?int $duration = null,
        ?int $length = null,
        InputFile|string|null $thumbnail = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendVideoNote', [
            'chat_id' => $chat_id,
            'video_note' => $video_note,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'ephemeral_message_parameters' => $ephemeral_message_parameters,
            'duration' => $duration,
            'length' => $length,
            'thumbnail' => $thumbnail,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'suggested_post_parameters' => $suggested_post_parameters,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function sendPaidMedia(
        int|string $chat_id,
        int $star_count,
        array $media,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?string $payload = null,
        ?string $caption = null,
        ?string $parse_mode = null,
        ?array $caption_entities = null,
        ?bool $show_caption_above_media = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        ReplyKeyboardRemove|ForceReply|InlineKeyboardMarkup|ReplyKeyboardMarkup|null $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendPaidMedia', [
            'chat_id' => $chat_id,
            'star_count' => $star_count,
            'media' => $media,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'payload' => $payload,
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'caption_entities' => $caption_entities,
            'show_caption_above_media' => $show_caption_above_media,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'suggested_post_parameters' => $suggested_post_parameters,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function sendMediaGroup(
        int|string $chat_id,
        array $media,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?ReplyParameters $reply_parameters = null,
    ): array {
        return Message::collect(
            $this->call('sendMediaGroup', [
                'chat_id' => $chat_id,
                'media' => $media,
                'business_connection_id' => $business_connection_id,
                'message_thread_id' => $message_thread_id,
                'direct_messages_topic_id' => $direct_messages_topic_id,
                'disable_notification' => $disable_notification,
                'protect_content' => $protect_content,
                'allow_paid_broadcast' => $allow_paid_broadcast,
                'message_effect_id' => $message_effect_id,
                'reply_parameters' => $reply_parameters,
            ])
        );
    }

    public function sendLocation(
        int|string $chat_id,
        float $latitude,
        float $longitude,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?EphemeralMessageParameters $ephemeral_message_parameters = null,
        ?float $horizontal_accuracy = null,
        ?int $live_period = null,
        ?int $heading = null,
        ?int $proximity_alert_radius = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendLocation', [
            'chat_id' => $chat_id,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'ephemeral_message_parameters' => $ephemeral_message_parameters,
            'horizontal_accuracy' => $horizontal_accuracy,
            'live_period' => $live_period,
            'heading' => $heading,
            'proximity_alert_radius' => $proximity_alert_radius,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'suggested_post_parameters' => $suggested_post_parameters,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function sendVenue(
        int|string $chat_id,
        float $latitude,
        float $longitude,
        string $title,
        string $address,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?EphemeralMessageParameters $ephemeral_message_parameters = null,
        ?string $foursquare_id = null,
        ?string $foursquare_type = null,
        ?string $google_place_id = null,
        ?string $google_place_type = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendVenue', [
            'chat_id' => $chat_id,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'title' => $title,
            'address' => $address,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'ephemeral_message_parameters' => $ephemeral_message_parameters,
            'foursquare_id' => $foursquare_id,
            'foursquare_type' => $foursquare_type,
            'google_place_id' => $google_place_id,
            'google_place_type' => $google_place_type,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'suggested_post_parameters' => $suggested_post_parameters,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function sendContact(
        int|string $chat_id,
        string $phone_number,
        string $first_name,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?EphemeralMessageParameters $ephemeral_message_parameters = null,
        ?string $last_name = null,
        ?string $vcard = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendContact', [
            'chat_id' => $chat_id,
            'phone_number' => $phone_number,
            'first_name' => $first_name,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'ephemeral_message_parameters' => $ephemeral_message_parameters,
            'last_name' => $last_name,
            'vcard' => $vcard,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'suggested_post_parameters' => $suggested_post_parameters,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function sendPoll(
        int|string $chat_id,
        string $question,
        array $options,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?string $question_parse_mode = null,
        ?array $question_entities = null,
        ?bool $is_anonymous = null,
        ?string $type = null,
        ?bool $allows_multiple_answers = null,
        ?bool $allows_revoting = null,
        ?bool $shuffle_options = null,
        ?bool $allow_adding_options = null,
        ?bool $hide_results_until_closes = null,
        ?bool $members_only = null,
        ?array $country_codes = null,
        ?array $correct_option_ids = null,
        ?string $explanation = null,
        ?string $explanation_parse_mode = null,
        ?array $explanation_entities = null,
        ?InputPollMedia $explanation_media = null,
        ?int $open_period = null,
        ?int $close_date = null,
        ?bool $is_closed = null,
        ?string $description = null,
        ?string $description_parse_mode = null,
        ?array $description_entities = null,
        ?InputPollMedia $media = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?ReplyParameters $reply_parameters = null,
        ReplyKeyboardRemove|ForceReply|InlineKeyboardMarkup|ReplyKeyboardMarkup|null $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendPoll', [
            'chat_id' => $chat_id,
            'question' => $question,
            'options' => $options,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'question_parse_mode' => $question_parse_mode,
            'question_entities' => $question_entities,
            'is_anonymous' => $is_anonymous,
            'type' => $type,
            'allows_multiple_answers' => $allows_multiple_answers,
            'allows_revoting' => $allows_revoting,
            'shuffle_options' => $shuffle_options,
            'allow_adding_options' => $allow_adding_options,
            'hide_results_until_closes' => $hide_results_until_closes,
            'members_only' => $members_only,
            'country_codes' => $country_codes,
            'correct_option_ids' => $correct_option_ids,
            'explanation' => $explanation,
            'explanation_parse_mode' => $explanation_parse_mode,
            'explanation_entities' => $explanation_entities,
            'explanation_media' => $explanation_media,
            'open_period' => $open_period,
            'close_date' => $close_date,
            'is_closed' => $is_closed,
            'description' => $description,
            'description_parse_mode' => $description_parse_mode,
            'description_entities' => $description_entities,
            'media' => $media,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function sendChecklist(
        string $business_connection_id,
        int|string $chat_id,
        InputChecklist $checklist,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?string $message_effect_id = null,
        ?ReplyParameters $reply_parameters = null,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendChecklist', [
            'business_connection_id' => $business_connection_id,
            'chat_id' => $chat_id,
            'checklist' => $checklist,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'message_effect_id' => $message_effect_id,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function sendDice(
        int|string $chat_id,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?string $emoji = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        ReplyKeyboardRemove|ForceReply|InlineKeyboardMarkup|ReplyKeyboardMarkup|null $reply_markup = null,
    ): Message {
        return Message::from($this->call('sendDice', [
            'chat_id' => $chat_id,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
            'direct_messages_topic_id' => $direct_messages_topic_id,
            'emoji' => $emoji,
            'disable_notification' => $disable_notification,
            'protect_content' => $protect_content,
            'allow_paid_broadcast' => $allow_paid_broadcast,
            'message_effect_id' => $message_effect_id,
            'suggested_post_parameters' => $suggested_post_parameters,
            'reply_parameters' => $reply_parameters,
            'reply_markup' => $reply_markup,
        ]));
    }

    public function sendMessageDraft(
        int $chat_id,
        int $draft_id,
        ?int $message_thread_id = null,
        ?string $text = null,
        ?string $parse_mode = null,
        ?array $entities = null,
        ?bool $can_stop = null,
        ?bool $keep_on_stop = null,
    ): true {
        return $this->call('sendMessageDraft', [
            'chat_id' => $chat_id,
            'draft_id' => $draft_id,
            'message_thread_id' => $message_thread_id,
            'text' => $text,
            'parse_mode' => $parse_mode,
            'entities' => $entities,
            'can_stop' => $can_stop,
            'keep_on_stop' => $keep_on_stop,
        ]);
    }

    public function sendChatAction(
        int|string $chat_id,
        string $action,
        ?string $business_connection_id = null,
        ?int $message_thread_id = null,
    ): true {
        return $this->call('sendChatAction', [
            'chat_id' => $chat_id,
            'action' => $action,
            'business_connection_id' => $business_connection_id,
            'message_thread_id' => $message_thread_id,
        ]);
    }

    public function setMessageReaction(
        int|string $chat_id,
        int $message_id,
        ?array $reaction = null,
        ?bool $is_big = null,
    ): true {
        return $this->call('setMessageReaction', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'reaction' => $reaction,
            'is_big' => $is_big,
        ]);
    }

    public function getUserProfilePhotos(int $user_id, ?int $offset = null, ?int $limit = null): UserProfilePhotos
    {
        return UserProfilePhotos::from($this->call('getUserProfilePhotos', [
            'user_id' => $user_id,
            'offset' => $offset,
            'limit' => $limit,
        ]));
    }

    public function getUserProfileAudios(int $user_id, ?int $offset = null, ?int $limit = null): UserProfileAudios
    {
        return UserProfileAudios::from($this->call('getUserProfileAudios', [
            'user_id' => $user_id,
            'offset' => $offset,
            'limit' => $limit,
        ]));
    }

    public function setUserEmojiStatus(
        int $user_id,
        ?string $emoji_status_custom_emoji_id = null,
        ?int $emoji_status_expiration_date = null,
    ): true {
        return $this->call('setUserEmojiStatus', [
            'user_id' => $user_id,
            'emoji_status_custom_emoji_id' => $emoji_status_custom_emoji_id,
            'emoji_status_expiration_date' => $emoji_status_expiration_date,
        ]);
    }

    public function getFile(string $file_id): File
    {
        return File::from($this->call('getFile', [
            'file_id' => $file_id,
        ]));
    }

    public function banChatMember(
        int|string $chat_id,
        int $user_id,
        ?int $until_date = null,
        ?bool $revoke_messages = null,
    ): true {
        return $this->call('banChatMember', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
            'until_date' => $until_date,
            'revoke_messages' => $revoke_messages,
        ]);
    }

    public function unbanChatMember(int|string $chat_id, int $user_id, ?bool $only_if_banned = null): true
    {
        return $this->call('unbanChatMember', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
            'only_if_banned' => $only_if_banned,
        ]);
    }

    public function restrictChatMember(
        int|string $chat_id,
        int $user_id,
        ChatPermissions $permissions,
        ?bool $use_independent_chat_permissions = null,
        ?int $until_date = null,
    ): true {
        return $this->call('restrictChatMember', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
            'permissions' => $permissions,
            'use_independent_chat_permissions' => $use_independent_chat_permissions,
            'until_date' => $until_date,
        ]);
    }

    public function promoteChatMember(
        int|string $chat_id,
        int $user_id,
        ?bool $is_anonymous = null,
        ?bool $can_manage_chat = null,
        ?bool $can_delete_messages = null,
        ?bool $can_manage_video_chats = null,
        ?bool $can_restrict_members = null,
        ?bool $can_promote_members = null,
        ?bool $can_change_info = null,
        ?bool $can_invite_users = null,
        ?bool $can_post_stories = null,
        ?bool $can_edit_stories = null,
        ?bool $can_delete_stories = null,
        ?bool $can_post_messages = null,
        ?bool $can_edit_messages = null,
        ?bool $can_pin_messages = null,
        ?bool $can_manage_topics = null,
        ?bool $can_manage_direct_messages = null,
        ?bool $can_manage_tags = null,
        ?bool $can_send_welcome_messages = null,
    ): true {
        return $this->call('promoteChatMember', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
            'is_anonymous' => $is_anonymous,
            'can_manage_chat' => $can_manage_chat,
            'can_delete_messages' => $can_delete_messages,
            'can_manage_video_chats' => $can_manage_video_chats,
            'can_restrict_members' => $can_restrict_members,
            'can_promote_members' => $can_promote_members,
            'can_change_info' => $can_change_info,
            'can_invite_users' => $can_invite_users,
            'can_post_stories' => $can_post_stories,
            'can_edit_stories' => $can_edit_stories,
            'can_delete_stories' => $can_delete_stories,
            'can_post_messages' => $can_post_messages,
            'can_edit_messages' => $can_edit_messages,
            'can_pin_messages' => $can_pin_messages,
            'can_manage_topics' => $can_manage_topics,
            'can_manage_direct_messages' => $can_manage_direct_messages,
            'can_manage_tags' => $can_manage_tags,
            'can_send_welcome_messages' => $can_send_welcome_messages,
        ]);
    }

    public function setChatAdministratorCustomTitle(int|string $chat_id, int $user_id, string $custom_title): true
    {
        return $this->call('setChatAdministratorCustomTitle', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
            'custom_title' => $custom_title,
        ]);
    }

    public function setChatMemberTag(int|string $chat_id, int $user_id, ?string $tag = null): true
    {
        return $this->call('setChatMemberTag', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
            'tag' => $tag,
        ]);
    }

    public function banChatSenderChat(int|string $chat_id, int $sender_chat_id): true
    {
        return $this->call('banChatSenderChat', [
            'chat_id' => $chat_id,
            'sender_chat_id' => $sender_chat_id,
        ]);
    }

    public function unbanChatSenderChat(int|string $chat_id, int $sender_chat_id): true
    {
        return $this->call('unbanChatSenderChat', [
            'chat_id' => $chat_id,
            'sender_chat_id' => $sender_chat_id,
        ]);
    }

    public function setChatPermissions(
        int|string $chat_id,
        ChatPermissions $permissions,
        ?bool $use_independent_chat_permissions = null,
    ): true {
        return $this->call('setChatPermissions', [
            'chat_id' => $chat_id,
            'permissions' => $permissions,
            'use_independent_chat_permissions' => $use_independent_chat_permissions,
        ]);
    }

    public function exportChatInviteLink(int|string $chat_id): string
    {
        return $this->call('exportChatInviteLink', [
            'chat_id' => $chat_id,
        ]);
    }

    public function createChatInviteLink(
        int|string $chat_id,
        ?string $name = null,
        ?int $expire_date = null,
        ?int $member_limit = null,
        ?bool $creates_join_request = null,
    ): ChatInviteLink {
        return ChatInviteLink::from($this->call('createChatInviteLink', [
            'chat_id' => $chat_id,
            'name' => $name,
            'expire_date' => $expire_date,
            'member_limit' => $member_limit,
            'creates_join_request' => $creates_join_request,
        ]));
    }

    public function editChatInviteLink(
        int|string $chat_id,
        string $invite_link,
        ?string $name = null,
        ?int $expire_date = null,
        ?int $member_limit = null,
        ?bool $creates_join_request = null,
    ): ChatInviteLink {
        return ChatInviteLink::from($this->call('editChatInviteLink', [
            'chat_id' => $chat_id,
            'invite_link' => $invite_link,
            'name' => $name,
            'expire_date' => $expire_date,
            'member_limit' => $member_limit,
            'creates_join_request' => $creates_join_request,
        ]));
    }

    public function createChatSubscriptionInviteLink(
        int|string $chat_id,
        int $subscription_period,
        int $subscription_price,
        ?string $name = null,
    ): ChatInviteLink {
        return ChatInviteLink::from($this->call('createChatSubscriptionInviteLink', [
            'chat_id' => $chat_id,
            'subscription_period' => $subscription_period,
            'subscription_price' => $subscription_price,
            'name' => $name,
        ]));
    }

    public function editChatSubscriptionInviteLink(
        int|string $chat_id,
        string $invite_link,
        ?string $name = null,
    ): ChatInviteLink {
        return ChatInviteLink::from($this->call('editChatSubscriptionInviteLink', [
            'chat_id' => $chat_id,
            'invite_link' => $invite_link,
            'name' => $name,
        ]));
    }

    public function revokeChatInviteLink(int|string $chat_id, string $invite_link): ChatInviteLink
    {
        return ChatInviteLink::from($this->call('revokeChatInviteLink', [
            'chat_id' => $chat_id,
            'invite_link' => $invite_link,
        ]));
    }

    public function approveChatJoinRequest(int|string $chat_id, int $user_id): true
    {
        return $this->call('approveChatJoinRequest', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ]);
    }

    public function declineChatJoinRequest(int|string $chat_id, int $user_id): true
    {
        return $this->call('declineChatJoinRequest', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ]);
    }

    public function answerChatJoinRequestQuery(string $chat_join_request_query_id, string $result): true
    {
        return $this->call('answerChatJoinRequestQuery', [
            'chat_join_request_query_id' => $chat_join_request_query_id,
            'result' => $result,
        ]);
    }

    public function sendChatJoinRequestWebApp(string $chat_join_request_query_id, string $web_app_url): true
    {
        return $this->call('sendChatJoinRequestWebApp', [
            'chat_join_request_query_id' => $chat_join_request_query_id,
            'web_app_url' => $web_app_url,
        ]);
    }

    public function setChatPhoto(int|string $chat_id, InputFile $photo): true
    {
        return $this->call('setChatPhoto', [
            'chat_id' => $chat_id,
            'photo' => $photo,
        ]);
    }

    public function deleteChatPhoto(int|string $chat_id): true
    {
        return $this->call('deleteChatPhoto', [
            'chat_id' => $chat_id,
        ]);
    }

    public function setChatTitle(int|string $chat_id, string $title): true
    {
        return $this->call('setChatTitle', [
            'chat_id' => $chat_id,
            'title' => $title,
        ]);
    }

    public function setChatDescription(int|string $chat_id, ?string $description = null): true
    {
        return $this->call('setChatDescription', [
            'chat_id' => $chat_id,
            'description' => $description,
        ]);
    }

    public function pinChatMessage(
        int|string $chat_id,
        int $message_id,
        ?string $business_connection_id = null,
        ?bool $disable_notification = null,
    ): true {
        return $this->call('pinChatMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'business_connection_id' => $business_connection_id,
            'disable_notification' => $disable_notification,
        ]);
    }

    public function unpinChatMessage(
        int|string $chat_id,
        ?string $business_connection_id = null,
        ?int $message_id = null,
    ): true {
        return $this->call('unpinChatMessage', [
            'chat_id' => $chat_id,
            'business_connection_id' => $business_connection_id,
            'message_id' => $message_id,
        ]);
    }

    public function unpinAllChatMessages(int|string $chat_id): true
    {
        return $this->call('unpinAllChatMessages', [
            'chat_id' => $chat_id,
        ]);
    }

    public function leaveChat(int|string $chat_id): true
    {
        return $this->call('leaveChat', [
            'chat_id' => $chat_id,
        ]);
    }

    public function getChat(int|string $chat_id): ChatFullInfo
    {
        return ChatFullInfo::from($this->call('getChat', [
            'chat_id' => $chat_id,
        ]));
    }

    public function getChatAdministrators(int|string $chat_id, ?bool $return_bots = null): array
    {
        $response = $this->call('getChatAdministrators', [
            'chat_id' => $chat_id,
            'return_bots' => $return_bots,
        ]);

        foreach ($response as &$item) {
            $item = match ($response['status']) {
                'creator' => ChatMemberOwner::from($response),
                'administrator' => ChatMemberAdministrator::from($response),
                'member' => ChatMemberMember::from($response),
                'restricted' => ChatMemberRestricted::from($response),
                'left' => ChatMemberLeft::from($response),
                'kicked' => ChatMemberBanned::from($response),
            };
        }

        return $response;
    }

    public function getChatMemberCount(int|string $chat_id): int
    {
        return $this->call('getChatMemberCount', [
            'chat_id' => $chat_id,
        ]);
    }

    public function getChatMember(int|string $chat_id, int $user_id): ChatMember
    {
        $response = $this->call('getChatMember', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ]);

        return match ($response['status']) {
            'creator' => ChatMemberOwner::from($response),
            'administrator' => ChatMemberAdministrator::from($response),
            'member' => ChatMemberMember::from($response),
            'restricted' => ChatMemberRestricted::from($response),
            'left' => ChatMemberLeft::from($response),
            'kicked' => ChatMemberBanned::from($response),
        };
    }

    public function getUserPersonalChatMessages(int $user_id, int $limit): array
    {
        return Message::collect(
            $this->call('getUserPersonalChatMessages', [
                'user_id' => $user_id,
                'limit' => $limit,
            ])
        );
    }

    public function setChatStickerSet(int|string $chat_id, string $sticker_set_name): true
    {
        return $this->call('setChatStickerSet', [
            'chat_id' => $chat_id,
            'sticker_set_name' => $sticker_set_name,
        ]);
    }

    public function deleteChatStickerSet(int|string $chat_id): true
    {
        return $this->call('deleteChatStickerSet', [
            'chat_id' => $chat_id,
        ]);
    }

    public function getForumTopicIconStickers(): array
    {
        return Sticker::collect($this->call('getForumTopicIconStickers'));
    }

    public function createForumTopic(
        int|string $chat_id,
        string $name,
        ?int $icon_color = null,
        ?string $icon_custom_emoji_id = null,
    ): ForumTopic {
        return ForumTopic::from($this->call('createForumTopic', [
            'chat_id' => $chat_id,
            'name' => $name,
            'icon_color' => $icon_color,
            'icon_custom_emoji_id' => $icon_custom_emoji_id,
        ]));
    }

    public function editForumTopic(
        int|string $chat_id,
        int $message_thread_id,
        ?string $name = null,
        ?string $icon_custom_emoji_id = null,
    ): true {
        return $this->call('editForumTopic', [
            'chat_id' => $chat_id,
            'message_thread_id' => $message_thread_id,
            'name' => $name,
            'icon_custom_emoji_id' => $icon_custom_emoji_id,
        ]);
    }

    public function closeForumTopic(int|string $chat_id, int $message_thread_id): true
    {
        return $this->call('closeForumTopic', [
            'chat_id' => $chat_id,
            'message_thread_id' => $message_thread_id,
        ]);
    }

    public function reopenForumTopic(int|string $chat_id, int $message_thread_id): true
    {
        return $this->call('reopenForumTopic', [
            'chat_id' => $chat_id,
            'message_thread_id' => $message_thread_id,
        ]);
    }

    public function deleteForumTopic(int|string $chat_id, int $message_thread_id): true
    {
        return $this->call('deleteForumTopic', [
            'chat_id' => $chat_id,
            'message_thread_id' => $message_thread_id,
        ]);
    }

    public function unpinAllForumTopicMessages(int|string $chat_id, int $message_thread_id): true
    {
        return $this->call('unpinAllForumTopicMessages', [
            'chat_id' => $chat_id,
            'message_thread_id' => $message_thread_id,
        ]);
    }

    public function editGeneralForumTopic(int|string $chat_id, string $name): true
    {
        return $this->call('editGeneralForumTopic', [
            'chat_id' => $chat_id,
            'name' => $name,
        ]);
    }

    public function closeGeneralForumTopic(int|string $chat_id): true
    {
        return $this->call('closeGeneralForumTopic', [
            'chat_id' => $chat_id,
        ]);
    }

    public function reopenGeneralForumTopic(int|string $chat_id): true
    {
        return $this->call('reopenGeneralForumTopic', [
            'chat_id' => $chat_id,
        ]);
    }

    public function hideGeneralForumTopic(int|string $chat_id): true
    {
        return $this->call('hideGeneralForumTopic', [
            'chat_id' => $chat_id,
        ]);
    }

    public function unhideGeneralForumTopic(int|string $chat_id): true
    {
        return $this->call('unhideGeneralForumTopic', [
            'chat_id' => $chat_id,
        ]);
    }

    public function unpinAllGeneralForumTopicMessages(int|string $chat_id): true
    {
        return $this->call('unpinAllGeneralForumTopicMessages', [
            'chat_id' => $chat_id,
        ]);
    }

    public function answerCallbackQuery(
        string $callback_query_id,
        ?string $text = null,
        ?bool $show_alert = null,
        ?string $url = null,
        ?int $cache_time = null,
    ): true {
        return $this->call('answerCallbackQuery', [
            'callback_query_id' => $callback_query_id,
            'text' => $text,
            'show_alert' => $show_alert,
            'url' => $url,
            'cache_time' => $cache_time,
        ]);
    }

    public function answerGuestQuery(string $guest_query_id, InlineQueryResult $result): SentGuestMessage
    {
        return SentGuestMessage::from($this->call('answerGuestQuery', [
            'guest_query_id' => $guest_query_id,
            'result' => $result,
        ]));
    }

    public function getUserChatBoosts(int|string $chat_id, int $user_id): UserChatBoosts
    {
        return UserChatBoosts::from($this->call('getUserChatBoosts', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ]));
    }

    public function getBusinessConnection(string $business_connection_id): BusinessConnection
    {
        return BusinessConnection::from($this->call('getBusinessConnection', [
            'business_connection_id' => $business_connection_id,
        ]));
    }

    public function getManagedBotToken(int $user_id): string
    {
        return $this->call('getManagedBotToken', [
            'user_id' => $user_id,
        ]);
    }

    public function replaceManagedBotToken(int $user_id): string
    {
        return $this->call('replaceManagedBotToken', [
            'user_id' => $user_id,
        ]);
    }

    public function getManagedBotAccessSettings(int $user_id): BotAccessSettings
    {
        return BotAccessSettings::from($this->call('getManagedBotAccessSettings', [
            'user_id' => $user_id,
        ]));
    }

    public function setManagedBotAccessSettings(
        int $user_id,
        bool $is_access_restricted,
        ?array $added_user_ids = null,
    ): true {
        return $this->call('setManagedBotAccessSettings', [
            'user_id' => $user_id,
            'is_access_restricted' => $is_access_restricted,
            'added_user_ids' => $added_user_ids,
        ]);
    }

    public function setMyCommands(
        array $commands,
        ?BotCommandScope $scope = null,
        ?string $language_code = null,
    ): true {
        return $this->call('setMyCommands', [
            'commands' => $commands,
            'scope' => $scope,
            'language_code' => $language_code,
        ]);
    }

    public function deleteMyCommands(?BotCommandScope $scope = null, ?string $language_code = null): true
    {
        return $this->call('deleteMyCommands', [
            'scope' => $scope,
            'language_code' => $language_code,
        ]);
    }

    public function getMyCommands(?BotCommandScope $scope = null, ?string $language_code = null): array
    {
        return BotCommand::collect(
            $this->call('getMyCommands', [
                'scope' => $scope,
                'language_code' => $language_code,
            ])
        );
    }

    public function setMyName(?string $name = null, ?string $language_code = null): true
    {
        return $this->call('setMyName', [
            'name' => $name,
            'language_code' => $language_code,
        ]);
    }

    public function getMyName(?string $language_code = null): BotName
    {
        return BotName::from($this->call('getMyName', [
            'language_code' => $language_code,
        ]));
    }

    public function setMyDescription(?string $description = null, ?string $language_code = null): true
    {
        return $this->call('setMyDescription', [
            'description' => $description,
            'language_code' => $language_code,
        ]);
    }

    public function getMyDescription(?string $language_code = null): BotDescription
    {
        return BotDescription::from($this->call('getMyDescription', [
            'language_code' => $language_code,
        ]));
    }

    public function setMyShortDescription(?string $short_description = null, ?string $language_code = null): true
    {
        return $this->call('setMyShortDescription', [
            'short_description' => $short_description,
            'language_code' => $language_code,
        ]);
    }

    public function getMyShortDescription(?string $language_code = null): BotShortDescription
    {
        return BotShortDescription::from($this->call('getMyShortDescription', [
            'language_code' => $language_code,
        ]));
    }

    public function setMyProfilePhoto(InputProfilePhoto $photo): true
    {
        return $this->call('setMyProfilePhoto', [
            'photo' => $photo,
        ]);
    }

    public function removeMyProfilePhoto(): true
    {
        return $this->call('removeMyProfilePhoto');
    }

    public function setChatMenuButton(?int $chat_id = null, ?MenuButton $menu_button = null): true
    {
        return $this->call('setChatMenuButton', [
            'chat_id' => $chat_id,
            'menu_button' => $menu_button,
        ]);
    }

    public function getChatMenuButton(?int $chat_id = null): MenuButton
    {
        $response = $this->call('getChatMenuButton', [
            'chat_id' => $chat_id,
        ]);

        return match ($response['type']) {
            'commands' => MenuButtonCommands::from($response),
            'web_app' => MenuButtonWebApp::from($response),
            'default' => MenuButtonDefault::from($response),
            default => Uncastable::create(),
        };
    }

    public function setMyDefaultAdministratorRights(
        ?ChatAdministratorRights $rights = null,
        ?bool $for_channels = null,
    ): true {
        return $this->call('setMyDefaultAdministratorRights', [
            'rights' => $rights,
            'for_channels' => $for_channels,
        ]);
    }

    public function getMyDefaultAdministratorRights(?bool $for_channels = null): ChatAdministratorRights
    {
        return ChatAdministratorRights::from($this->call('getMyDefaultAdministratorRights', [
            'for_channels' => $for_channels,
        ]));
    }

    public function getAvailableGifts(): Gifts
    {
        return Gifts::from($this->call('getAvailableGifts'));
    }

    public function sendGift(
        string $gift_id,
        ?int $user_id = null,
        int|string|null $chat_id = null,
        ?bool $pay_for_upgrade = null,
        ?string $text = null,
        ?string $text_parse_mode = null,
        ?array $text_entities = null,
    ): true {
        return $this->call('sendGift', [
            'gift_id' => $gift_id,
            'user_id' => $user_id,
            'chat_id' => $chat_id,
            'pay_for_upgrade' => $pay_for_upgrade,
            'text' => $text,
            'text_parse_mode' => $text_parse_mode,
            'text_entities' => $text_entities,
        ]);
    }

    public function giftPremiumSubscription(
        int $user_id,
        int $month_count,
        int $star_count,
        ?string $text = null,
        ?string $text_parse_mode = null,
        ?array $text_entities = null,
    ): true {
        return $this->call('giftPremiumSubscription', [
            'user_id' => $user_id,
            'month_count' => $month_count,
            'star_count' => $star_count,
            'text' => $text,
            'text_parse_mode' => $text_parse_mode,
            'text_entities' => $text_entities,
        ]);
    }

    public function verifyUser(int $user_id, ?string $custom_description = null): true
    {
        return $this->call('verifyUser', [
            'user_id' => $user_id,
            'custom_description' => $custom_description,
        ]);
    }

    public function verifyChat(int|string $chat_id, ?string $custom_description = null): true
    {
        return $this->call('verifyChat', [
            'chat_id' => $chat_id,
            'custom_description' => $custom_description,
        ]);
    }

    public function removeUserVerification(int $user_id): true
    {
        return $this->call('removeUserVerification', [
            'user_id' => $user_id,
        ]);
    }

    public function removeChatVerification(int|string $chat_id): true
    {
        return $this->call('removeChatVerification', [
            'chat_id' => $chat_id,
        ]);
    }

    public function readBusinessMessage(string $business_connection_id, int $chat_id, int $message_id): true
    {
        return $this->call('readBusinessMessage', [
            'business_connection_id' => $business_connection_id,
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ]);
    }

    public function deleteBusinessMessages(string $business_connection_id, array $message_ids): true
    {
        return $this->call('deleteBusinessMessages', [
            'business_connection_id' => $business_connection_id,
            'message_ids' => $message_ids,
        ]);
    }

    public function setBusinessAccountName(
        string $business_connection_id,
        string $first_name,
        ?string $last_name = null,
    ): true {
        return $this->call('setBusinessAccountName', [
            'business_connection_id' => $business_connection_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
        ]);
    }

    public function setBusinessAccountUsername(string $business_connection_id, ?string $username = null): true
    {
        return $this->call('setBusinessAccountUsername', [
            'business_connection_id' => $business_connection_id,
            'username' => $username,
        ]);
    }

    public function setBusinessAccountBio(string $business_connection_id, ?string $bio = null): true
    {
        return $this->call('setBusinessAccountBio', [
            'business_connection_id' => $business_connection_id,
            'bio' => $bio,
        ]);
    }

    public function setBusinessAccountProfilePhoto(
        string $business_connection_id,
        InputProfilePhoto $photo,
        ?bool $is_public = null,
    ): true {
        return $this->call('setBusinessAccountProfilePhoto', [
            'business_connection_id' => $business_connection_id,
            'photo' => $photo,
            'is_public' => $is_public,
        ]);
    }

    public function removeBusinessAccountProfilePhoto(string $business_connection_id, ?bool $is_public = null): true
    {
        return $this->call('removeBusinessAccountProfilePhoto', [
            'business_connection_id' => $business_connection_id,
            'is_public' => $is_public,
        ]);
    }

    public function setBusinessAccountGiftSettings(
        string $business_connection_id,
        bool $show_gift_button,
        AcceptedGiftTypes $accepted_gift_types,
    ): true {
        return $this->call('setBusinessAccountGiftSettings', [
            'business_connection_id' => $business_connection_id,
            'show_gift_button' => $show_gift_button,
            'accepted_gift_types' => $accepted_gift_types,
        ]);
    }

    public function getBusinessAccountStarBalance(string $business_connection_id): StarAmount
    {
        return StarAmount::from($this->call('getBusinessAccountStarBalance', [
            'business_connection_id' => $business_connection_id,
        ]));
    }

    public function transferBusinessAccountStars(string $business_connection_id, int $star_count): true
    {
        return $this->call('transferBusinessAccountStars', [
            'business_connection_id' => $business_connection_id,
            'star_count' => $star_count,
        ]);
    }

    public function getBusinessAccountGifts(
        string $business_connection_id,
        ?bool $exclude_unsaved = null,
        ?bool $exclude_saved = null,
        ?bool $exclude_unlimited = null,
        ?bool $exclude_limited_upgradable = null,
        ?bool $exclude_limited_non_upgradable = null,
        ?bool $exclude_unique = null,
        ?bool $exclude_from_blockchain = null,
        ?bool $sort_by_price = null,
        ?string $offset = null,
        ?int $limit = null,
    ): OwnedGifts {
        return OwnedGifts::from($this->call('getBusinessAccountGifts', [
            'business_connection_id' => $business_connection_id,
            'exclude_unsaved' => $exclude_unsaved,
            'exclude_saved' => $exclude_saved,
            'exclude_unlimited' => $exclude_unlimited,
            'exclude_limited_upgradable' => $exclude_limited_upgradable,
            'exclude_limited_non_upgradable' => $exclude_limited_non_upgradable,
            'exclude_unique' => $exclude_unique,
            'exclude_from_blockchain' => $exclude_from_blockchain,
            'sort_by_price' => $sort_by_price,
            'offset' => $offset,
            'limit' => $limit,
        ]));
    }

    public function getUserGifts(
        int $user_id,
        ?bool $exclude_unlimited = null,
        ?bool $exclude_limited_upgradable = null,
        ?bool $exclude_limited_non_upgradable = null,
        ?bool $exclude_from_blockchain = null,
        ?bool $exclude_unique = null,
        ?bool $sort_by_price = null,
        ?string $offset = null,
        ?int $limit = null,
    ): OwnedGifts {
        return OwnedGifts::from($this->call('getUserGifts', [
            'user_id' => $user_id,
            'exclude_unlimited' => $exclude_unlimited,
            'exclude_limited_upgradable' => $exclude_limited_upgradable,
            'exclude_limited_non_upgradable' => $exclude_limited_non_upgradable,
            'exclude_from_blockchain' => $exclude_from_blockchain,
            'exclude_unique' => $exclude_unique,
            'sort_by_price' => $sort_by_price,
            'offset' => $offset,
            'limit' => $limit,
        ]));
    }

    public function getChatGifts(
        int|string $chat_id,
        ?bool $exclude_unsaved = null,
        ?bool $exclude_saved = null,
        ?bool $exclude_unlimited = null,
        ?bool $exclude_limited_upgradable = null,
        ?bool $exclude_limited_non_upgradable = null,
        ?bool $exclude_from_blockchain = null,
        ?bool $exclude_unique = null,
        ?bool $sort_by_price = null,
        ?string $offset = null,
        ?int $limit = null,
    ): OwnedGifts {
        return OwnedGifts::from($this->call('getChatGifts', [
            'chat_id' => $chat_id,
            'exclude_unsaved' => $exclude_unsaved,
            'exclude_saved' => $exclude_saved,
            'exclude_unlimited' => $exclude_unlimited,
            'exclude_limited_upgradable' => $exclude_limited_upgradable,
            'exclude_limited_non_upgradable' => $exclude_limited_non_upgradable,
            'exclude_from_blockchain' => $exclude_from_blockchain,
            'exclude_unique' => $exclude_unique,
            'sort_by_price' => $sort_by_price,
            'offset' => $offset,
            'limit' => $limit,
        ]));
    }

    public function convertGiftToStars(string $business_connection_id, string $owned_gift_id): true
    {
        return $this->call('convertGiftToStars', [
            'business_connection_id' => $business_connection_id,
            'owned_gift_id' => $owned_gift_id,
        ]);
    }

    public function upgradeGift(
        string $business_connection_id,
        string $owned_gift_id,
        ?bool $keep_original_details = null,
        ?int $star_count = null,
    ): true {
        return $this->call('upgradeGift', [
            'business_connection_id' => $business_connection_id,
            'owned_gift_id' => $owned_gift_id,
            'keep_original_details' => $keep_original_details,
            'star_count' => $star_count,
        ]);
    }

    public function transferGift(
        string $business_connection_id,
        string $owned_gift_id,
        int $new_owner_chat_id,
        ?int $star_count = null,
    ): true {
        return $this->call('transferGift', [
            'business_connection_id' => $business_connection_id,
            'owned_gift_id' => $owned_gift_id,
            'new_owner_chat_id' => $new_owner_chat_id,
            'star_count' => $star_count,
        ]);
    }

    public function postStory(
        string $business_connection_id,
        InputStoryContent $content,
        int $active_period,
        ?string $caption = null,
        ?string $parse_mode = null,
        ?array $caption_entities = null,
        ?array $areas = null,
        ?bool $post_to_chat_page = null,
        ?bool $protect_content = null,
    ): Story {
        return Story::from($this->call('postStory', [
            'business_connection_id' => $business_connection_id,
            'content' => $content,
            'active_period' => $active_period,
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'caption_entities' => $caption_entities,
            'areas' => $areas,
            'post_to_chat_page' => $post_to_chat_page,
            'protect_content' => $protect_content,
        ]));
    }

    public function repostStory(
        string $business_connection_id,
        int $from_chat_id,
        int $from_story_id,
        int $active_period,
        ?bool $post_to_chat_page = null,
        ?bool $protect_content = null,
    ): Story {
        return Story::from($this->call('repostStory', [
            'business_connection_id' => $business_connection_id,
            'from_chat_id' => $from_chat_id,
            'from_story_id' => $from_story_id,
            'active_period' => $active_period,
            'post_to_chat_page' => $post_to_chat_page,
            'protect_content' => $protect_content,
        ]));
    }

    public function editStory(
        string $business_connection_id,
        int $story_id,
        InputStoryContent $content,
        ?string $caption = null,
        ?string $parse_mode = null,
        ?array $caption_entities = null,
        ?array $areas = null,
    ): Story {
        return Story::from($this->call('editStory', [
            'business_connection_id' => $business_connection_id,
            'story_id' => $story_id,
            'content' => $content,
            'caption' => $caption,
            'parse_mode' => $parse_mode,
            'caption_entities' => $caption_entities,
            'areas' => $areas,
        ]));
    }

    public function deleteStory(string $business_connection_id, int $story_id): true
    {
        return $this->call('deleteStory', [
            'business_connection_id' => $business_connection_id,
            'story_id' => $story_id,
        ]);
    }

    public function answerWebAppQuery(string $web_app_query_id, InlineQueryResult $result): SentWebAppMessage
    {
        return SentWebAppMessage::from($this->call('answerWebAppQuery', [
            'web_app_query_id' => $web_app_query_id,
            'result' => $result,
        ]));
    }

    public function savePreparedInlineMessage(
        int $user_id,
        InlineQueryResult $result,
        ?bool $allow_user_chats = null,
        ?bool $allow_bot_chats = null,
        ?bool $allow_group_chats = null,
        ?bool $allow_channel_chats = null,
    ): PreparedInlineMessage {
        return PreparedInlineMessage::from($this->call('savePreparedInlineMessage', [
            'user_id' => $user_id,
            'result' => $result,
            'allow_user_chats' => $allow_user_chats,
            'allow_bot_chats' => $allow_bot_chats,
            'allow_group_chats' => $allow_group_chats,
            'allow_channel_chats' => $allow_channel_chats,
        ]));
    }

    public function savePreparedKeyboardButton(int $user_id, KeyboardButton $button): PreparedKeyboardButton
    {
        return PreparedKeyboardButton::from($this->call('savePreparedKeyboardButton', [
            'user_id' => $user_id,
            'button' => $button,
        ]));
    }
}

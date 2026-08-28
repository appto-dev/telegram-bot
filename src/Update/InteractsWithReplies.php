<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Update;

use Appto\TelegramBot\Client\Enums\ChatAction;
use Appto\TelegramBot\Client\Enums\DiceEmoji;
use Appto\TelegramBot\Type\EphemeralMessageParameters;
use Appto\TelegramBot\Type\ForceReply;
use Appto\TelegramBot\Type\InlineKeyboardMarkup;
use Appto\TelegramBot\Type\InputChecklist;
use Appto\TelegramBot\Type\InputFile;
use Appto\TelegramBot\Type\InputPollMedia;
use Appto\TelegramBot\Type\LinkPreviewOptions;
use Appto\TelegramBot\Type\Message;
use Appto\TelegramBot\Type\ReplyKeyboardMarkup;
use Appto\TelegramBot\Type\ReplyKeyboardRemove;
use Appto\TelegramBot\Type\ReplyParameters;
use Appto\TelegramBot\Type\SuggestedPostParameters;
use InvalidArgumentException;

trait InteractsWithReplies
{
    public function reply(
        string $text,
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
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendMessage(
            chat_id: $chat_id,
            text: $text,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            direct_messages_topic_id: $direct_messages_topic_id,
            ephemeral_message_parameters: $ephemeral_message_parameters,
            parse_mode: $parse_mode,
            entities: $entities,
            link_preview_options: $link_preview_options,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            message_effect_id: $message_effect_id,
            suggested_post_parameters: $suggested_post_parameters,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyPhoto(
        InputFile|string $photo,
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
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendPhoto(
            chat_id: $chat_id,
            photo: $photo,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            direct_messages_topic_id: $direct_messages_topic_id,
            ephemeral_message_parameters: $ephemeral_message_parameters,
            caption: $caption,
            parse_mode: $parse_mode,
            caption_entities: $caption_entities,
            show_caption_above_media: $show_caption_above_media,
            has_spoiler: $has_spoiler,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            message_effect_id: $message_effect_id,
            suggested_post_parameters: $suggested_post_parameters,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyLivePhoto(
        InputFile|string $live_photo,
        InputFile|string $photo,
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
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendLivePhoto(
            chat_id: $chat_id,
            live_photo: $live_photo,
            photo: $photo,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            direct_messages_topic_id: $direct_messages_topic_id,
            ephemeral_message_parameters: $ephemeral_message_parameters,
            caption: $caption,
            parse_mode: $parse_mode,
            caption_entities: $caption_entities,
            show_caption_above_media: $show_caption_above_media,
            has_spoiler: $has_spoiler,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            message_effect_id: $message_effect_id,
            suggested_post_parameters: $suggested_post_parameters,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyAudio(
        InputFile|string $audio,
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
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendAudio(
            chat_id: $chat_id,
            audio: $audio,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            direct_messages_topic_id: $direct_messages_topic_id,
            ephemeral_message_parameters: $ephemeral_message_parameters,
            caption: $caption,
            parse_mode: $parse_mode,
            caption_entities: $caption_entities,
            duration: $duration,
            performer: $performer,
            title: $title,
            thumbnail: $thumbnail,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            message_effect_id: $message_effect_id,
            suggested_post_parameters: $suggested_post_parameters,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyDocument(
        InputFile|string $document,
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
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendDocument(
            chat_id: $chat_id,
            document: $document,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            direct_messages_topic_id: $direct_messages_topic_id,
            ephemeral_message_parameters: $ephemeral_message_parameters,
            thumbnail: $thumbnail,
            caption: $caption,
            parse_mode: $parse_mode,
            caption_entities: $caption_entities,
            disable_content_type_detection: $disable_content_type_detection,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            message_effect_id: $message_effect_id,
            suggested_post_parameters: $suggested_post_parameters,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyVideo(
        InputFile|string $video,
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
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendVideo(
            chat_id: $chat_id,
            video: $video,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            direct_messages_topic_id: $direct_messages_topic_id,
            ephemeral_message_parameters: $ephemeral_message_parameters,
            duration: $duration,
            width: $width,
            height: $height,
            thumbnail: $thumbnail,
            cover: $cover,
            start_timestamp: $start_timestamp,
            caption: $caption,
            parse_mode: $parse_mode,
            caption_entities: $caption_entities,
            show_caption_above_media: $show_caption_above_media,
            has_spoiler: $has_spoiler,
            supports_streaming: $supports_streaming,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            message_effect_id: $message_effect_id,
            suggested_post_parameters: $suggested_post_parameters,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyAnimation(
        InputFile|string $animation,
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
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendAnimation(
            chat_id: $chat_id,
            animation: $animation,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            direct_messages_topic_id: $direct_messages_topic_id,
            ephemeral_message_parameters: $ephemeral_message_parameters,
            duration: $duration,
            width: $width,
            height: $height,
            thumbnail: $thumbnail,
            caption: $caption,
            parse_mode: $parse_mode,
            caption_entities: $caption_entities,
            show_caption_above_media: $show_caption_above_media,
            has_spoiler: $has_spoiler,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            message_effect_id: $message_effect_id,
            suggested_post_parameters: $suggested_post_parameters,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyVoice(
        InputFile|string $voice,
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
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendVoice(
            chat_id: $chat_id,
            voice: $voice,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            direct_messages_topic_id: $direct_messages_topic_id,
            ephemeral_message_parameters: $ephemeral_message_parameters,
            caption: $caption,
            parse_mode: $parse_mode,
            caption_entities: $caption_entities,
            duration: $duration,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            message_effect_id: $message_effect_id,
            suggested_post_parameters: $suggested_post_parameters,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyVideoNote(
        InputFile|string $video_note,
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
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendVideoNote(
            chat_id: $chat_id,
            video_note: $video_note,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            direct_messages_topic_id: $direct_messages_topic_id,
            ephemeral_message_parameters: $ephemeral_message_parameters,
            duration: $duration,
            length: $length,
            thumbnail: $thumbnail,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            message_effect_id: $message_effect_id,
            suggested_post_parameters: $suggested_post_parameters,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyPaidMedia(
        int $star_count,
        array $media,
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
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendPaidMedia(
            chat_id: $chat_id,
            star_count: $star_count,
            media: $media,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            direct_messages_topic_id: $direct_messages_topic_id,
            payload: $payload,
            caption: $caption,
            parse_mode: $parse_mode,
            caption_entities: $caption_entities,
            show_caption_above_media: $show_caption_above_media,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            suggested_post_parameters: $suggested_post_parameters,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyMediaGroup(
        array $media,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?ReplyParameters $reply_parameters = null,
    ): array {
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendMediaGroup(
            chat_id: $chat_id,
            media: $media,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            direct_messages_topic_id: $direct_messages_topic_id,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            message_effect_id: $message_effect_id,
            reply_parameters: $reply_parameters,
        );
    }

    public function replyLocation(
        float $latitude,
        float $longitude,
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
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendLocation(
            chat_id: $chat_id,
            latitude: $latitude,
            longitude: $longitude,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            direct_messages_topic_id: $direct_messages_topic_id,
            ephemeral_message_parameters: $ephemeral_message_parameters,
            horizontal_accuracy: $horizontal_accuracy,
            live_period: $live_period,
            heading: $heading,
            proximity_alert_radius: $proximity_alert_radius,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            message_effect_id: $message_effect_id,
            suggested_post_parameters: $suggested_post_parameters,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyVenue(
        float $latitude,
        float $longitude,
        string $title,
        string $address,
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
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendVenue(
            chat_id: $chat_id,
            latitude: $latitude,
            longitude: $longitude,
            title: $title,
            address: $address,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            direct_messages_topic_id: $direct_messages_topic_id,
            ephemeral_message_parameters: $ephemeral_message_parameters,
            foursquare_id: $foursquare_id,
            foursquare_type: $foursquare_type,
            google_place_id: $google_place_id,
            google_place_type: $google_place_type,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            message_effect_id: $message_effect_id,
            suggested_post_parameters: $suggested_post_parameters,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyContact(
        string $phone_number,
        string $first_name,
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
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendContact(
            chat_id: $chat_id,
            phone_number: $phone_number,
            first_name: $first_name,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            direct_messages_topic_id: $direct_messages_topic_id,
            ephemeral_message_parameters: $ephemeral_message_parameters,
            last_name: $last_name,
            vcard: $vcard,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            message_effect_id: $message_effect_id,
            suggested_post_parameters: $suggested_post_parameters,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyPoll(
        string $question,
        array $options,
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
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendPoll(
            chat_id: $chat_id,
            question: $question,
            options: $options,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            question_parse_mode: $question_parse_mode,
            question_entities: $question_entities,
            is_anonymous: $is_anonymous,
            type: $type,
            allows_multiple_answers: $allows_multiple_answers,
            allows_revoting: $allows_revoting,
            shuffle_options: $shuffle_options,
            allow_adding_options: $allow_adding_options,
            hide_results_until_closes: $hide_results_until_closes,
            members_only: $members_only,
            country_codes: $country_codes,
            correct_option_ids: $correct_option_ids,
            explanation: $explanation,
            explanation_parse_mode: $explanation_parse_mode,
            explanation_entities: $explanation_entities,
            explanation_media: $explanation_media,
            open_period: $open_period,
            close_date: $close_date,
            is_closed: $is_closed,
            description: $description,
            description_parse_mode: $description_parse_mode,
            description_entities: $description_entities,
            media: $media,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            message_effect_id: $message_effect_id,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyChecklist(
        InputChecklist $checklist,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?string $message_effect_id = null,
        ?ReplyParameters $reply_parameters = null,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): Message {
        [$chat_id, $business_connection_id] = $this->getReplyData();

        if (! $business_connection_id) {
            throw new InvalidArgumentException('Business connection id is required');
        }

        return $this->client()->sendChecklist(
            business_connection_id: $business_connection_id,
            chat_id: $chat_id,
            checklist: $checklist,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            message_effect_id: $message_effect_id,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyDice(
        ?DiceEmoji $emoji = null,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        ReplyKeyboardRemove|ForceReply|InlineKeyboardMarkup|ReplyKeyboardMarkup|null $reply_markup = null,
    ): Message {
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendDice(
            chat_id: $chat_id,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
            direct_messages_topic_id: $direct_messages_topic_id,
            emoji: $emoji?->value,
            disable_notification: $disable_notification,
            protect_content: $protect_content,
            allow_paid_broadcast: $allow_paid_broadcast,
            message_effect_id: $message_effect_id,
            suggested_post_parameters: $suggested_post_parameters,
            reply_parameters: $reply_parameters,
            reply_markup: $reply_markup,
        );
    }

    public function replyMessageDraft(
        int $draft_id,
        ?int $message_thread_id = null,
        ?string $text = null,
        ?string $parse_mode = null,
        ?array $entities = null,
        ?bool $can_stop = null,
        ?bool $keep_on_stop = null,
    ): true {
        [$chat_id] = $this->getReplyData();

        return $this->client()->sendMessageDraft(
            chat_id: $chat_id,
            draft_id: $draft_id,
            message_thread_id: $message_thread_id,
            text: $text,
            parse_mode: $parse_mode,
            entities: $entities,
            can_stop: $can_stop,
            keep_on_stop: $keep_on_stop,
        );
    }

    public function replyChatAction(ChatAction $action, ?int $message_thread_id = null): true
    {
        [$chat_id, $business_connection_id] = $this->getReplyData();

        return $this->client()->sendChatAction(
            chat_id: $chat_id,
            action: $action->value,
            business_connection_id: $business_connection_id,
            message_thread_id: $message_thread_id,
        );
    }

    public function answerCallbackQuery(
        ?string $text = null,
        ?bool $show_alert = null,
        ?string $url = null,
        ?int $cache_time = null,
    ): bool {
        if ($this->update->callback_query === null) {
            return false;
        }

        return $this->client()->answerCallbackQuery(
            callback_query_id: $this->update->callback_query->id,
            text: $text,
            show_alert: $show_alert,
            url: $url,
            cache_time: $cache_time,
        );
    }

    /** @return array {int, string|null} chat_id, business_connection_id */
    public function getReplyData(): array
    {
        $type = UpdateType::detect($this->update);

        if ($type === UpdateType::CALLBACK_QUERY) {
            $callback_query = $this->update->callback_query;
            $message = $callback_query->message;
        } else {
            $message = $this->message();
        }

        return [
            $this->chatId(),
            $message?->business_connection_id,
        ];
    }
}

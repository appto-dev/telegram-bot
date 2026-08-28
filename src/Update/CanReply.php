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
use Appto\TelegramBot\Type\InputMediaAudio;
use Appto\TelegramBot\Type\InputMediaDocument;
use Appto\TelegramBot\Type\InputMediaLivePhoto;
use Appto\TelegramBot\Type\InputMediaPhoto;
use Appto\TelegramBot\Type\InputMediaVideo;
use Appto\TelegramBot\Type\InputPaidMedia;
use Appto\TelegramBot\Type\InputPollMedia;
use Appto\TelegramBot\Type\InputPollOption;
use Appto\TelegramBot\Type\LinkPreviewOptions;
use Appto\TelegramBot\Type\Message;
use Appto\TelegramBot\Type\MessageEntity;
use Appto\TelegramBot\Type\ReplyKeyboardMarkup;
use Appto\TelegramBot\Type\ReplyKeyboardRemove;
use Appto\TelegramBot\Type\ReplyParameters;
use Appto\TelegramBot\Type\SuggestedPostParameters;

interface CanReply
{
    /**
     * Use this method to send text messages. On success, the sent
     * <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param  string  $text  Text of the message to be sent, 1-4096 characters after entities parsing
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  int|null  $direct_messages_topic_id  Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     * @param  EphemeralMessageParameters|null  $ephemeral_message_parameters  A JSON-serialized object containing the parameters of the ephemeral message to send
     * @param  string|null  $parse_mode  Mode for parsing entities in the message text. See <a href="#formatting-options">formatting options</a> for more details.
     * @param  MessageEntity[]|null  $entities  A JSON-serialized list of special entities that appear in message text, which can be specified instead of <em>parse_mode</em>
     * @param  LinkPreviewOptions|null  $link_preview_options  Link preview generation options for the message
     * @param  bool|null  $disable_notification  Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding and saving
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the message; for private chats only
     * @param  SuggestedPostParameters|null  $suggested_post_parameters  A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @param  InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null  $reply_markup  Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
     */
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
    ): Message;

    /**
     * Use this method to send photos. On success, the sent
     * <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param  InputFile|string  $photo  Photo to send. Pass a file_id as String to send a photo that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a photo from the Internet, or upload a new photo using multipart/form-data. The photo must be at most 10 MB in size. The photo's width and height must not exceed 10000 in total. Width and height ratio must be at  most 20. <a href="#sending-files">More information on Sending Files </a>
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  int|null  $direct_messages_topic_id  Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     * @param  EphemeralMessageParameters|null  $ephemeral_message_parameters  A JSON-serialized object containing the parameters of the ephemeral message to send @param  string|null $caption Photo caption (may also be used when resending photos by <em>file_id</em>), 0-1024 characters after entities parsing
     * @param  string|null  $parse_mode  Mode for parsing entities in the photo caption. See <a href="#formatting-options">formatting options</a> for more details.
     * @param  MessageEntity[]|null  $caption_entities  A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
     * @param  bool|null  $show_caption_above_media  Pass <em>True</em> if the caption must be shown above the message media
     * @param  bool|null  $has_spoiler  Pass <em>True</em> if the photo needs to be covered with a spoiler animation
     * @param  bool|null  $disable_notification  Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding and saving
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the message; for private chats only
     * @param  SuggestedPostParameters|null  $suggested_post_parameters  A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @param  InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null  $reply_markup  Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
     */
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
    ): Message;

    /**
     * Use this method to send live photos. On success, the sent
     * <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param  InputFile|string  $live_photo  Live photo video to send. The video must be no longer than 10 seconds and must not exceed 10 MB in size. Pass a file_id as String to send a video that exists on the Telegram servers (recommended) or upload a new video using multipart/form-data. <a href="#sending-files">More information on Sending Files </a>. Sending live photos by a URL is currently unsupported.
     * @param  InputFile|string  $photo  The static photo to send. Pass a file_id as String to send a photo that exists on the Telegram servers (recommended) or upload a new video using multipart/form-data. <a href="#sending-files">More information on Sending Files </a>. Sending live photos by a URL is currently unsupported.
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  int|null  $direct_messages_topic_id  Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     * @param  EphemeralMessageParameters|null  $ephemeral_message_parameters  A JSON-serialized object containing the parameters of the ephemeral message to send
     * @param  string|null  $caption  Video caption (may also be used when resending videos by <em>file_id</em>), 0-1024 characters after entities parsing
     * @param  string|null  $parse_mode  Mode for parsing entities in the video caption. See <a href="#formatting-options">formatting options</a> for more details.
     * @param  MessageEntity[]|null  $caption_entities  A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
     * @param  bool|null  $show_caption_above_media  Pass <em>True</em> if the caption must be shown above the message media
     * @param  bool|null  $has_spoiler  Pass <em>True</em> if the video needs to be covered with a spoiler animation
     * @param  bool|null  $disable_notification  Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding and saving
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the message; for private chats only
     * @param  SuggestedPostParameters|null  $suggested_post_parameters  A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @param  InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null  $reply_markup  Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
     */
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
    ): Message;

    /**
     * Use this method to send audio files, if you want Telegram clients to display them in the music
     * player. Your audio must be in the .MP3 or .M4A format. On success, the sent
     * <a href="https://core.telegram.org/bots/api#message">Message</a> is returned. Bots can currently
     * send audio files of up to 50 MB in size, this limit may be changed in the future.
     * For sending voice messages, use the
     * <a href="https://core.telegram.org/bots/api#sendvoice">sendVoice</a> method instead.
     *
     * @param  InputFile|string  $audio  Audio file to send. Pass a file_id as String to send an audio file that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get an audio file from the Internet, or upload a new one using multipart/form-data. <a href="#sending-files">More information on Sending Files </a>
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  int|null  $direct_messages_topic_id  Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     * @param  EphemeralMessageParameters|null  $ephemeral_message_parameters  A JSON-serialized object containing the parameters of the ephemeral message to send
     * @param  string|null  $caption  Audio caption, 0-1024 characters after entities parsing
     * @param  string|null  $parse_mode  Mode for parsing entities in the audio caption. See <a href="#formatting-options">formatting options</a> for more details.
     * @param  MessageEntity[]|null  $caption_entities  A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
     * @param  int|null  $duration  Duration of the audio in seconds
     * @param  string|null  $performer  Performer
     * @param  string|null  $title  Track name
     * @param  InputFile|string|null  $thumbnail  Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass "attach://<file_attach_name>" if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. <a href="#sending-files">More information on Sending Files </a>
     * @param  bool|null  $disable_notification  Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding and saving
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the message; for private chats only
     * @param  SuggestedPostParameters|null  $suggested_post_parameters  A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @param  InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null  $reply_markup  Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a replykeyboard or to force a reply from the user.
     */
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
    ): Message;

    /**
     * Use this method to send general files. On success, the sent
     * <a href="https://core.telegram.org/bots/api#message">Message</a> is returned. Bots can currently
     * send files of any type of up to 50 MB in size, this limit may be changed in the future.
     *
     * @param  InputFile|string  $document  File to send. Pass a file_id as String to send a file that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a file from the Internet, or upload a new one using multipart/form-data. <a href="#sending-files">More information on Sending Files </a>
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  int|null  $direct_messages_topic_id  Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     * @param  EphemeralMessageParameters|null  $ephemeral_message_parameters  A JSON-serialized object containing the parameters of the ephemeral message to send
     * @param  InputFile|string|null  $thumbnail  Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass "attach://<file_attach_name>" if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. <a href="#sending-files">More information on Sending Files </a>
     * @param  string|null  $caption  Document caption (may also be used when resending documents by <em>file_id</em>), 0-1024 characters after entities parsing
     * @param  string|null  $parse_mode  Mode for parsing entities in the document caption. See <a href="#formatting-options">formatting options</a> for more details.
     * @param  MessageEntity[]|null  $caption_entities  A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
     * @param  bool|null  $disable_content_type_detection  Disables automatic server-side content type detection for files uploaded using multipart/form-data
     * @param  bool|null  $disable_notification  Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding and saving
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the message; for private chats only
     * @param  SuggestedPostParameters|null  $suggested_post_parameters  A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @param  InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null  $reply_markup  Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
     */
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
    ): Message;

    /**
     * Use this method to send video files, Telegram clients support MPEG4 videos (other formats may be
     * sent as <a href="https://core.telegram.org/bots/api#document">Document</a>). On success, the sent
     * <a href="https://core.telegram.org/bots/api#message">Message</a> is returned. Bots can currently
     * send video files of up to 50 MB in size, this limit may be changed in the future.
     *
     * @param  InputFile|string  $video  Video to send. Pass a file_id as String to send a video that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a video from the Internet, or upload a new video using multipart/form-data. <a href="#sending-files">More information on Sending Files </a>
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  int|null  $direct_messages_topic_id  Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     * @param  EphemeralMessageParameters|null  $ephemeral_message_parameters  A JSON-serialized object containing the parameters of the ephemeral message to send
     * @param  int|null  $duration  Duration of sent video in seconds
     * @param  int|null  $width  Video width
     * @param  int|null  $height  Video height
     * @param  InputFile|string|null  $thumbnail  Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass "attach://<file_attach_name>" if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. <a href="#sending-files">More information on Sending Files </a>
     * @param  InputFile|string|null  $cover  Cover for the video in the message. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass "attach://<file_attach_name>" to upload a new one using multipart/form-data under <file_attach_name> name. <a href="#sending-files">More information on Sending Files </a>
     * @param  int|null  $start_timestamp  Start timestamp for the video in the message
     * @param  string|null  $caption  Video caption (may also be used when resending videos by <em>file_id</em>), 0-1024 characters after entities parsing
     * @param  string|null  $parse_mode  Mode for parsing entities in the video caption. See <a href="#formatting-options">formatting options</a> for more details.
     * @param  MessageEntity[]|null  $caption_entities  A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
     * @param  bool|null  $show_caption_above_media  Pass <em>True</em> if the caption must be shown above the message media
     * @param  bool|null  $has_spoiler  Pass <em>True</em> if the video needs to be covered with a spoiler animation
     * @param  bool|null  $supports_streaming  Pass <em>True</em> if the uploaded video is suitable for streaming
     * @param  bool|null  $disable_notification  Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding and saving
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the message; for private chats only
     * @param  SuggestedPostParameters|null  $suggested_post_parameters  A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @param  InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null  $reply_markup  Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
     */
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
    ): Message;

    /**
     * Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound). On success,
     * the sent <a href="https://core.telegram.org/bots/api#message">Message</a> is returned. Bots can
     * currently send animation files of up to 50 MB in size, this limit may be changed in the future.
     *
     * @param  InputFile|string  $animation  Animation to send. Pass a file_id as String to send an animation that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get an animation from the Internet, or upload a new animation using multipart/form-data. <a href="#sending-files">More information on Sending Files </a>
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  int|null  $direct_messages_topic_id  Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     * @param  EphemeralMessageParameters|null  $ephemeral_message_parameters  A JSON-serialized object containing the parameters of the ephemeral message to send
     * @param  int|null  $duration  Duration of sent animation in seconds
     * @param  int|null  $width  Animation width
     * @param  int|null  $height  Animation height
     * @param  InputFile|string|null  $thumbnail  Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass "attach://<file_attach_name>" if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. <a href="#sending-files">More information on Sending Files </a>
     * @param  string|null  $caption  Animation caption (may also be used when resending animation by <em>file_id</em>), 0-1024 characters after entities parsing
     * @param  string|null  $parse_mode  Mode for parsing entities in the animation caption. See <a href="#formatting-options">formatting options</a> for more details.
     * @param  MessageEntity[]|null  $caption_entities  A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
     * @param  bool|null  $show_caption_above_media  Pass <em>True</em> if the caption must be shown above the message media
     * @param  bool|null  $has_spoiler  Pass <em>True</em> if the animation needs to be covered with a spoiler animation
     * @param  bool|null  $disable_notification  Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding and saving
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the message; for private chats only
     * @param  SuggestedPostParameters|null  $suggested_post_parameters  A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @param  InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null  $reply_markup  Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
     */
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
    ): Message;

    /**
     * Use this method to send audio files, if you want Telegram clients to display the file as a playable
     * voice message. For this to work, your audio must be in an .OGG file encoded with OPUS, or in .MP3
     * format, or in .M4A format (other formats may be sent as
     * <a href="https://core.telegram.org/bots/api#audio">Audio</a> or
     * <a href="https://core.telegram.org/bots/api#document">Document</a>). On success, the sent
     * <a href="https://core.telegram.org/bots/api#message">Message</a> is returned. Bots can currently
     * send voice messages of up to 50 MB in size, this limit may be changed in the future.
     *
     * @param  InputFile|string  $voice  Audio file to send. Pass a file_id as String to send a file that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a file from the Internet, or upload a new one using multipart/form-data. <a href="#sending-files">More information on Sending Files </a>
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  int|null  $direct_messages_topic_id  Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     * @param  EphemeralMessageParameters|null  $ephemeral_message_parameters  A JSON-serialized object containing the parameters of the ephemeral message to send
     * @param  string|null  $caption  Voice message caption, 0-1024 characters after entities parsing
     * @param  string|null  $parse_mode  Mode for parsing entities in the voice message caption. See <a href="#formatting-options">formatting options</a> for more details.
     * @param  MessageEntity[]|null  $caption_entities  A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
     * @param  int|null  $duration  Duration of the voice message in seconds
     * @param  bool|null  $disable_notification  Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding and saving
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the message; for private chats only
     * @param  SuggestedPostParameters|null  $suggested_post_parameters  A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @param  InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null  $reply_markup  Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
     */
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
    ): Message;

    /**
     * Use this method to send a rounded square MPEG4 video of up to 1 minute long. On success, the sent
     * <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param  InputFile|string  $video_note  Video note to send. Pass a file_id as String to send a video note that exists on the Telegram servers (recommended) or upload a new video using multipart/form-data. <a href="#sending-files">More information on Sending Files </a>. Sending video notes by a URL is currently unsupported.
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  int|null  $direct_messages_topic_id  Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     * @param  EphemeralMessageParameters|null  $ephemeral_message_parameters  A JSON-serialized object containing the parameters of the ephemeral message to send
     * @param  int|null  $duration  Duration of sent video in seconds
     * @param  int|null  $length  Video width and height, i.e. diameter of the video message
     * @param  InputFile|string|null  $thumbnail  Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass "attach://<file_attach_name>" if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. <a href="#sending-files">More information on Sending Files </a>
     * @param  bool|null  $disable_notification  Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding and saving
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the message; for private chats only
     * @param  SuggestedPostParameters|null  $suggested_post_parameters  A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @param  InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null  $reply_markup  Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
     */
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
    ): Message;

    /**
     * Use this method to send paid media. On success, the sent
     * <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param  int  $star_count  The number of Telegram Stars that must be paid to buy access to the media; 1-25000
     * @param  InputPaidMedia[]  $media  A JSON-serialized Array describing the media to be sent; up to 10 items
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  int|null  $direct_messages_topic_id  Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     * @param  string|null  $payload  Bot-defined paid media payload, 0-128 bytes. This will not be displayed to the user, use it for your internal processes.
     * @param  string|null  $caption  Media caption, 0-1024 characters after entities parsing
     * @param  string|null  $parse_mode  Mode for parsing entities in the media caption. See <a href="#formatting-options">formatting options</a> for more details.
     * @param  MessageEntity[]|null  $caption_entities  A JSON-serialized list of special entities that appear in the caption, which can be specified instead of <em>parse_mode</em>
     * @param  bool|null  $show_caption_above_media  Pass <em>True</em> if the caption must be shown above the message media
     * @param  bool|null  $disable_notification  Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding and saving
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  SuggestedPostParameters|null  $suggested_post_parameters  A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @param  InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null  $reply_markup  Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
     */
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
    ): Message;

    /**
     * Use this method to send a group of photos, live photos, videos, documents or audios as an album.
     * Documents and audio files can be only grouped in an album with messages of the same type. On
     * success, an Array of <a href="https://core.telegram.org/bots/api#message">Message</a> objects that
     * were sent is returned.
     *
     * @param  (InputMediaAudio|InputMediaDocument|InputMediaLivePhoto|InputMediaPhoto|InputMediaVideo)[]  $media  A JSON-serialized Array describing messages to be sent, must include 2-10 items
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  int|null  $direct_messages_topic_id  Identifier of the direct messages topic to which the messages will be sent; required if the messages are sent to a direct messages chat
     * @param  bool|null  $disable_notification  Sends messages <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent messages from forwarding and saving
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the message; for private chats only
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @return Message[]
     */
    public function replyMediaGroup(
        array $media,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?ReplyParameters $reply_parameters = null,
    ): array;

    /**
     * Use this method to send point on the map. On success, the sent
     * <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param  float  $latitude  Latitude of the location
     * @param  float  $longitude  Longitude of the location
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  int|null  $direct_messages_topic_id  Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     * @param  EphemeralMessageParameters|null  $ephemeral_message_parameters  A JSON-serialized object containing the parameters of the ephemeral message to send
     * @param  float|null  $horizontal_accuracy  The radius of uncertainty for the location, measured in meters; 0-1500
     * @param  int|null  $live_period  Period in seconds during which the location will be updated (see <a href="https://telegram.org/blog/live-locations">Live Locations</a>), must be between 60 and 86400, or 0x7FFFFFFF for live locations that can be edited indefinitely. Must be 0 for ephemeral messages.
     * @param  int|null  $heading  For live locations, a direction in which the user is moving, in degrees. Must be between 1 and 360 if specified.
     * @param  int|null  $proximity_alert_radius  For live locations, a maximum distance for proximity alerts about approaching another chat member, in meters. Must be between 1 and 100000 if specified.
     * @param  bool|null  $disable_notification  Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding and saving
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the message; for private chats only
     * @param  SuggestedPostParameters|null  $suggested_post_parameters  A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @param  InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null  $reply_markup  Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
     */
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
    ): Message;

    /**
     * Use this method to send information about a venue. On success, the sent
     * <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param  float  $latitude  Latitude of the venue
     * @param  float  $longitude  Longitude of the venue
     * @param  string  $title  Name of the venue
     * @param  string  $address  Address of the venue
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  int|null  $direct_messages_topic_id  Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     * @param  EphemeralMessageParameters|null  $ephemeral_message_parameters  A JSON-serialized object containing the parameters of the ephemeral message to send
     * @param  string|null  $foursquare_id  Foursquare identifier of the venue
     * @param  string|null  $foursquare_type  Foursquare type of the venue, if known. (For example, "arts_entertainment/default", "arts_entertainment/aquarium" or "food/icecream".)
     * @param  string|null  $google_place_id  Google Places identifier of the venue
     * @param  string|null  $google_place_type  Google Places type of the venue. (See <a href="https://developers.google.com/places/web-service/supported_types">supported types</a>.)
     * @param  bool|null  $disable_notification  Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding and saving
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the message; for private chats only
     * @param  SuggestedPostParameters|null  $suggested_post_parameters  A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @param  InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null  $reply_markup  Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
     */
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
    ): Message;

    /**
     * Use this method to send phone contacts. On success, the sent
     * <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param  string  $phone_number  Contact's phone number
     * @param  string  $first_name  Contact's first name
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  int|null  $direct_messages_topic_id  Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     * @param  EphemeralMessageParameters|null  $ephemeral_message_parameters  A JSON-serialized object containing the parameters of the ephemeral message to send
     * @param  string|null  $last_name  Contact's last name
     * @param  string|null  $vcard  Additional data about the contact in the form of a <a href="https://en.wikipedia.org/wiki/VCard">vCard</a>, 0-2048 bytes
     * @param  bool|null  $disable_notification  Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding and saving
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the message; for private chats only
     * @param  SuggestedPostParameters|null  $suggested_post_parameters  A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @param  InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null  $reply_markup  Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
     */
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
    ): Message;

    /**
     * Use this method to send a native poll. On success, the sent
     * <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param  string  $question  Poll question, 1-300 characters
     * @param  InputPollOption[]  $options  A JSON-serialized list of 1-12 answer options
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  string|null  $question_parse_mode  Mode for parsing entities in the question. See  <a href="#formatting-options">formatting options</a> for more details. Currently, only custom emoji entities are allowed.
     * @param  MessageEntity[]|null  $question_entities  A JSON-serialized list of special entities that appear in the poll question. It can be specified instead of <em>question_parse_mode</em>.
     * @param  bool|null  $is_anonymous  <em>True</em>, if the poll needs to be anonymous, defaults to <em>True</em>
     * @param  string|null  $type  Poll type, "quiz" or "regular", defaults to "regular"
     * @param  bool|null  $allows_multiple_answers  Pass <em>True</em> if the poll allows multiple answers, defaults to <em>False</em>
     * @param  bool|null  $allows_revoting  Pass <em>True</em> if the poll allows to change chosen answer  options, defaults to <em>False</em> for quizzes and to <em>True</em> for regular polls
     * @param  bool|null  $shuffle_options  Pass <em>True</em> if the poll options must be shown in random order
     * @param  bool|null  $allow_adding_options  Pass <em>True</em> if answer options can be added to the poll after creation; not supported for anonymous polls and quizzes
     * @param  bool|null  $hide_results_until_closes  Pass <em>True</em> if poll results must be shown only after the poll closes
     * @param  bool|null  $members_only  Pass <em>True</em> if voting is limited to users who have been members of the chat where the poll is being sent for more than 24 hours; for channel chats only
     * @param  string[]|null  $country_codes  A JSON-serialized list of 0-12 two-letter <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">ISO 3166-1 alpha-2</a> country codes indicating the countries from which users can vote in the poll; for channel chats only. Use "FT" as a country code to allow users with anonymous numbers to vote. If omitted or empty, then users from any country can participate in the poll.
     * @param  int[]|null  $correct_option_ids  A JSON-serialized list of monotonically increasing 0-based identifiers of the correct answer options, required for polls in quiz mode
     * @param  string|null  $explanation  Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters with at most 2 line feeds after entities parsing
     * @param  string|null  $explanation_parse_mode  Mode for parsing entities in the explanation. See <a href="#formatting-options">formatting options</a> for more details.
     * @param  MessageEntity[]|null  $explanation_entities  A JSON-serialized list of special entities that appear in the poll explanation. It can be specified instead of <em>explanation_parse_mode</em>.
     * @param  InputPollMedia|null  $explanation_media  Media added to the quiz explanation
     * @param  int|null  $open_period  Amount of time in seconds the poll will be active after creation, 5-2628000. Can't be used together with <em>close_date</em>.
     * @param  int|null  $close_date  Point in time (Unix timestamp) when the poll will be automatically closed. Must be at least 5 and no more than 2628000 seconds in the future. Can't be used together with <em>open_period</em>.
     * @param  bool|null  $is_closed  Pass <em>True</em> if the poll needs to be immediately closed. This can be useful for poll preview.
     * @param  string|null  $description  Description of the poll to be sent, 0-1024 characters after entities parsing
     * @param  string|null  $description_parse_mode  Mode for parsing entities in the poll description. See <a href="#formatting-options">formatting options</a> for more details.
     * @param  MessageEntity[]|null  $description_entities  A JSON-serialized list of special entities that appear in the poll description, which can be specified instead of <em>description_parse_mode</em>
     * @param  InputPollMedia|null  $media  Media added to the poll description
     * @param  bool|null  $disable_notification  Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding and saving
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the message; for private chats only
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @param  InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null  $reply_markup  Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
     */
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
    ): Message;

    /**
     * Use this method to send a checklist on behalf of a connected business account. On success, the sent
     * <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param  InputChecklist  $checklist  A JSON-serialized object for the checklist to send
     * @param  bool|null  $disable_notification  Sends the message silently. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding and saving
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the  message
     * @param  ReplyParameters|null  $reply_parameters  A JSON-serialized object for description of the message to reply to
     * @param  InlineKeyboardMarkup|null  $reply_markup  A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>
     */
    public function replyChecklist(
        InputChecklist $checklist,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?string $message_effect_id = null,
        ?ReplyParameters $reply_parameters = null,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): Message;

    /**
     * Use this method to send an animated emoji that will display a random value. On success, the sent
     * <a href="https://core.telegram.org/bots/api#message">Message</a> is returned.
     *
     * @param  ?DiceEmoji  $emoji  Emoji on which the dice throw animation is based. Currently, must be one of "🎲", "🎯", "🏀", "⚽", "🎳", or "🎰". Dice can have values 1-6 for "🎲", "🎯" and "🎳", values 1-5 for "🏀" and "⚽", and values 1-64 for "🎰". Defaults to "🎲".
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
     * @param  int|null  $direct_messages_topic_id  Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
     * @param  bool|null  $disable_notification  Sends the message <a href="https://telegram.org/blog/channels-2-0#silent-messages">silently</a>. Users will receive a notification with no sound.
     * @param  bool|null  $protect_content  Protects the contents of the sent message from forwarding
     * @param  bool|null  $allow_paid_broadcast  Pass <em>True</em> to allow up to 1000 messages per second, ignoring <a href="https://core.telegram.org/bots/faq#how-can-i-message-all-of-my-bot-39s-subscribers-at-once">broadcasting limits</a> for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
     * @param  string|null  $message_effect_id  Unique identifier of the message effect to be added to the message; for private chats only
     * @param  SuggestedPostParameters|null  $suggested_post_parameters  A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
     * @param  ReplyParameters|null  $reply_parameters  Description of the message to reply to
     * @param  InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null  $reply_markup  Additional interface options. A JSON-serialized object for an <a href="/bots/features#inline-keyboards">inline keyboard</a>, <a href="/bots/features#keyboards">custom reply keyboard</a>, instructions to remove a reply keyboard or to force a reply from the user.
     */
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
    ): Message;

    /**
     * Use this method to stream a partial message to a user while the message is being generated. Note
     * that the streamed draft is ephemeral and acts as a temporary 30-second preview - once the output is
     * finalized, you must call <a href="https://core.telegram.org/bots/api#sendmessage">sendMessage</a>
     * with the complete message to persist it in the user's chat. Returns <em>True</em> on success.
     *
     * @param  int  $draft_id  Unique identifier of the message draft; must be non-zero. Changes to drafts with the same identifier are animated. Otherwise, the draft is replaced without animation.
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread
     * @param  string|null  $text  Text of the message to be sent, 0-4096 characters after entities parsing.  Pass an empty text to show a "Thinking…" placeholder.
     * @param  string|null  $parse_mode  Mode for parsing entities in the message text. See <a href="#formatting-options">formatting options</a> for more details.
     * @param  MessageEntity[]|null  $entities  A JSON-serialized list of special entities that appear in essage text, which can be specified instead of <em>parse_mode</em>
     * @param  bool|null  $can_stop  Pass <em>True</em> to show the user a button to stop further drafts. The bot will receive an <a href="#update">Update</a> "stopped_message_generation" if the user presses the button.
     * @param  bool|null  $keep_on_stop  Pass <em>True</em> to keep the draft in the chat when the button is pressed. The draft will still disappear after a short time or if the bot sends a message. To fully preserve the partial draft, the bot should send it as a new message.
     */
    public function replyMessageDraft(
        int $draft_id,
        ?int $message_thread_id = null,
        ?string $text = null,
        ?string $parse_mode = null,
        ?array $entities = null,
        ?bool $can_stop = null,
        ?bool $keep_on_stop = null,
    ): true;

    /**
     * Use this method when you need to tell the user that something is happening on the bot's side. The
     * status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear
     * its typing status). Returns <em>True</em> on success.
     * Example: The <a href="https://t.me/imagebot">ImageBot</a> needs some time to process a request and
     * upload the image. Instead of sending a text message along the lines of "Retrieving image, please
     * wait…", the bot may use
     * <a href="https://core.telegram.org/bots/api#sendchataction">sendChatAction</a> with <em>action</em>
     * = <em>upload_photo</em>. The user will see a "sending photo" status for the bot.
     * We only recommend using this method when a response from the bot will take a noticeable amount of
     * time to arrive.
     *
     * @param  ChatAction  $action  Type of action to broadcast. Choose one, depending on what the user is about to receive: <em>typing</em> for <a href="#sendmessage">text messages</a>, <em>upload_photo</em> for <a href="#sendphoto">photos</a>, <em>record_video</em> or <em>upload_video</em> for <a href="#sendvideo">videos</a>, <em>record_voice</em> or <em>upload_voice</em> for <a href="#sendvoice">voice notes</a>, <em>upload_document</em> for <a href="#senddocument">general files</a>, <em>choose_sticker</em> for <a href="#sendsticker">stickers</a>, <em>find_location</em> for <a href="#sendlocation">location data</a>, <em>record_video_note</em> or <em>upload_video_note</em> for <a href="#sendvideonote">video notes</a>.
     * @param  int|null  $message_thread_id  Unique identifier for the target message thread or topic of a forum; for supergroups and private chats of bots with forum topic mode enabled only
     */
    public function replyChatAction(
        ChatAction $action,
        ?int $message_thread_id = null,
    ): true;

    /**
     * Use this method to send answers to callback queries sent from
     * <a href="https://core.telegram.org/bots/features#inline-keyboards">inline keyboards</a>. The answer
     * will be displayed to the user as a notification at the top of the chat screen or as an alert. On
     * success, <em>True</em> is returned.
     * Alternatively, the user can be redirected to the specified Game URL. For this option to work, you
     * must first create a game for your bot via <a href="https://t.me/botfather">@BotFather</a> and accept
     * the terms. Otherwise, you may use links like t.me/your_bot?start=XXXX that open your bot with a
     * parameter.
     *
     * @param  string|null  $text  Text of the notification. If not specified, nothing will be shown to the user, 0-200 characters.
     * @param  bool|null  $show_alert  If <em>True</em>, an alert will be shown by the client instead of a notification at the top of the chat screen. Defaults to <em>False</em>.
     * @param  string|null  $url  URL that will be opened by the user's client. If you have created a <a href="#game">Game</a> and accepted the conditions via <a href="https://t.me/botfather">@BotFather</a>, specify the URL that opens your game - note that this will only work if the query comes from a <a href="#inlinekeyboardbutton"><em>callback_game</em></a> button.<br><br>Otherwise, you may use links like <code>t.me/your_bot?start=XXXX</code> that open your bot with a parameter.
     * @param  int|null  $cache_time  The maximum amount of time in seconds that the result of the callback query may be cached client-side. Defaults to 0.
     * @return true
     */
    public function answerCallbackQuery(
        ?string $text = null,
        ?bool $show_alert = null,
        ?string $url = null,
        ?int $cache_time = null,
    ): bool;
}

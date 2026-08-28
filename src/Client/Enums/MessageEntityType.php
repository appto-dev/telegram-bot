<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client\Enums;

/**
 * Type of the entity. Currently, can be "mention" (@username), "hashtag" (#hashtag or
 * #hashtag@chatusername), "cashtag" ($USD or $USD@chatusername), "bot_command" (/start@jobs_bot),
 * "url" (https://telegram.org), "email" (do-not-reply@telegram.org), "phone_number" (+1-212-555-0123),
 * "bold" (bold text), "italic" (italic text), "underline" (underlined text), "strikethrough"
 * (strikethrough text), "spoiler" (spoiler message), "blockquote" (block quotation),
 * "expandable_blockquote" (collapsed-by-default block quotation), "code" (monowidth string), "pre"
 * (monowidth block), "text_link" (for clickable text URLs), "text_mention" (for users without
 * usernames), "custom_emoji" (for inline custom emoji stickers), or "date_time" (for formatted date
 * and time).
 */
enum MessageEntityType: string
{
    case MENTION = 'mention';
    case HASHTAG = 'hashtag';
    case CASHTAG = 'cashtag';
    case BOT_COMMAND = 'bot_command';
    case URL = 'url';
    case EMAIL = 'email';
    case PHONE_NUMBER = 'phone_number';
    case BOLD = 'bold';
    case ITALIC = 'italic';
    case UNDERLINE = 'underline';
    case STRIKETHROUGH = 'strikethrough';
    case SPOILER = 'spoiler';
    case BLOCKQUOTE = 'blockquote';
    case EXPANDABLE_BLOCKQUOTE = 'expandable_blockquote';
    case CODE = 'code';
    case PRE = 'pre';
    case TEXT_LINK = 'text_link';
    case TEXT_MENTION = 'text_mention';
    case CUSTOM_EMOJI = 'custom_emoji';
    case DATETIME = 'date_time';
}

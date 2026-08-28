<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Update;

use Appto\TelegramBot\Type\BotSubscriptionUpdated;
use Appto\TelegramBot\Type\BusinessConnection;
use Appto\TelegramBot\Type\BusinessMessagesDeleted;
use Appto\TelegramBot\Type\CallbackQuery;
use Appto\TelegramBot\Type\ChatBoostRemoved;
use Appto\TelegramBot\Type\ChatBoostUpdated;
use Appto\TelegramBot\Type\ChatJoinRequest;
use Appto\TelegramBot\Type\ChatMemberUpdated;
use Appto\TelegramBot\Type\ChosenInlineResult;
use Appto\TelegramBot\Type\InlineQuery;
use Appto\TelegramBot\Type\ManagedBotUpdated;
use Appto\TelegramBot\Type\Message;
use Appto\TelegramBot\Type\MessageReactionCountUpdated;
use Appto\TelegramBot\Type\MessageReactionUpdated;
use Appto\TelegramBot\Type\PaidMediaPurchased;
use Appto\TelegramBot\Type\Poll;
use Appto\TelegramBot\Type\PollAnswer;
use Appto\TelegramBot\Type\PreCheckoutQuery;
use Appto\TelegramBot\Type\ShippingQuery;
use Appto\TelegramBot\Type\TelegramType;
use Appto\TelegramBot\Type\Update;

/**
 * Полный список полей объекта Update из https://core.telegram.org/bots/api#update.
 * В каждом входящем апдейте заполнено ровно одно из этих полей — по нему и определяем тип.
 */
enum UpdateType: string
{
    case MESSAGE = 'message';
    case EDITED_MESSAGE = 'edited_message';
    case CHANNEL_POST = 'channel_post';
    case EDITED_CHANNEL_POST = 'edited_channel_post';
    case BUSINESS_CONNECTION = 'business_connection';
    case BUSINESS_MESSAGE = 'business_message';
    case EDITED_BUSINESS_MESSAGE = 'edited_business_message';
    case DELETED_BUSINESS_MESSAGES = 'deleted_business_messages';
    case GUEST_MESSAGE = 'guest_message';
    case MESSAGE_REACTION = 'message_reaction';
    case MESSAGE_REACTION_COUNT = 'message_reaction_count';
    case INLINE_QUERY = 'inline_query';
    case CHOSEN_INLINE_RESULT = 'chosen_inline_result';
    case CALLBACK_QUERY = 'callback_query';
    case SHIPPING_QUERY = 'shipping_query';
    case PRE_CHECKOUT_QUERY = 'pre_checkout_query';
    case PURCHASED_PAID_MEDIA = 'purchased_paid_media';
    case POLL = 'poll';
    case POLL_ANSWER = 'poll_answer';
    case MY_CHAT_MEMBER = 'my_chat_member';
    case CHAT_MEMBER = 'chat_member';
    case CHAT_JOIN_REQUEST = 'chat_join_request';
    case CHAT_BOOST = 'chat_boost';
    case REMOVED_CHAT_BOOST = 'removed_chat_boost';
    case MANAGER_BOT = 'managed_bot';
    case SUBSCRIPTION = 'subscription';

    /**
     * Определяет тип по Update-объекту: берёт первый ключ, отличный от update_id.
     * Если пришёл тип, которого ещё нет в этом enum (Telegram время от времени добавляет новые),
     * возвращает null — вызывающий код сам решает, что делать (обычно fallback()).
     * Текущий список по версии Bot API 10.2.
     */
    public static function detect(Update $update): ?self
    {
        foreach ($update as $key => $value) {
            if ($key === 'update_id' || self::tryFrom($key) && is_null($update->{$key})) {
                continue;
            }

            return self::tryFrom($key);
        }

        return null;
    }

    /**
     * Возвращает FQCN класса-полезной нагрузки, которым Telegram представляет данное поле Update.
     *
     * @return class-string<TelegramType>
     */
    public function payloadClass(): string
    {
        return match ($this) {
            self::MESSAGE, self::EDITED_MESSAGE, self::CHANNEL_POST, self::EDITED_CHANNEL_POST, self::BUSINESS_MESSAGE, self::EDITED_BUSINESS_MESSAGE, self::GUEST_MESSAGE => Message::class,
            self::BUSINESS_CONNECTION => BusinessConnection::class,
            self::DELETED_BUSINESS_MESSAGES => BusinessMessagesDeleted::class,
            self::MESSAGE_REACTION => MessageReactionUpdated::class,
            self::MESSAGE_REACTION_COUNT => MessageReactionCountUpdated::class,
            self::INLINE_QUERY => InlineQuery::class,
            self::CHOSEN_INLINE_RESULT => ChosenInlineResult::class,
            self::CALLBACK_QUERY => CallbackQuery::class,
            self::SHIPPING_QUERY => ShippingQuery::class,
            self::PRE_CHECKOUT_QUERY => PreCheckoutQuery::class,
            self::PURCHASED_PAID_MEDIA => PaidMediaPurchased::class,
            self::POLL => Poll::class,
            self::POLL_ANSWER => PollAnswer::class,
            self::MY_CHAT_MEMBER, self::CHAT_MEMBER => ChatMemberUpdated::class,
            self::CHAT_JOIN_REQUEST => ChatJoinRequest::class,
            self::CHAT_BOOST => ChatBoostUpdated::class,
            self::REMOVED_CHAT_BOOST => ChatBoostRemoved::class,
            self::MANAGER_BOT => ManagedBotUpdated::class,
            self::SUBSCRIPTION => BotSubscriptionUpdated::class,
        };
    }
}

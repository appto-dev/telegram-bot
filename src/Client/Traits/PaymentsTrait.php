<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client\Traits;

use Appto\TelegramBot\Type\InlineKeyboardMarkup;
use Appto\TelegramBot\Type\Message;
use Appto\TelegramBot\Type\ReplyParameters;
use Appto\TelegramBot\Type\StarAmount;
use Appto\TelegramBot\Type\StarTransactions;
use Appto\TelegramBot\Type\SuggestedPostParameters;

trait PaymentsTrait
{
    public function sendInvoice(
        int|string $chat_id,
        string $title,
        string $description,
        string $payload,
        string $currency,
        array $prices,
        ?int $message_thread_id = null,
        ?int $direct_messages_topic_id = null,
        ?string $provider_token = null,
        ?int $max_tip_amount = null,
        ?array $suggested_tip_amounts = null,
        ?string $start_parameter = null,
        ?string $provider_data = null,
        ?string $photo_url = null,
        ?int $photo_size = null,
        ?int $photo_width = null,
        ?int $photo_height = null,
        ?bool $need_name = null,
        ?bool $need_phone_number = null,
        ?bool $need_email = null,
        ?bool $need_shipping_address = null,
        ?bool $send_phone_number_to_provider = null,
        ?bool $send_email_to_provider = null,
        ?bool $is_flexible = null,
        ?bool $disable_notification = null,
        ?bool $protect_content = null,
        ?bool $allow_paid_broadcast = null,
        ?string $message_effect_id = null,
        ?SuggestedPostParameters $suggested_post_parameters = null,
        ?ReplyParameters $reply_parameters = null,
        ?InlineKeyboardMarkup $reply_markup = null,
    ): Message {
        return Message::from(
            $this->call('sendInvoice', [
                'chat_id' => $chat_id,
                'title' => $title,
                'description' => $description,
                'payload' => $payload,
                'currency' => $currency,
                'prices' => $prices,
                'message_thread_id' => $message_thread_id,
                'direct_messages_topic_id' => $direct_messages_topic_id,
                'provider_token' => $provider_token,
                'max_tip_amount' => $max_tip_amount,
                'suggested_tip_amounts' => $suggested_tip_amounts,
                'start_parameter' => $start_parameter,
                'provider_data' => $provider_data,
                'photo_url' => $photo_url,
                'photo_size' => $photo_size,
                'photo_width' => $photo_width,
                'photo_height' => $photo_height,
                'need_name' => $need_name,
                'need_phone_number' => $need_phone_number,
                'need_email' => $need_email,
                'need_shipping_address' => $need_shipping_address,
                'send_phone_number_to_provider' => $send_phone_number_to_provider,
                'send_email_to_provider' => $send_email_to_provider,
                'is_flexible' => $is_flexible,
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

    public function createInvoiceLink(
        string $title,
        string $description,
        string $payload,
        string $currency,
        array $prices,
        ?string $business_connection_id = null,
        ?string $provider_token = null,
        ?int $subscription_period = null,
        ?int $max_tip_amount = null,
        ?array $suggested_tip_amounts = null,
        ?string $provider_data = null,
        ?string $photo_url = null,
        ?int $photo_size = null,
        ?int $photo_width = null,
        ?int $photo_height = null,
        ?bool $need_name = null,
        ?bool $need_phone_number = null,
        ?bool $need_email = null,
        ?bool $need_shipping_address = null,
        ?bool $send_phone_number_to_provider = null,
        ?bool $send_email_to_provider = null,
        ?bool $is_flexible = null,
    ): string {
        return $this->call('createInvoiceLink', [
            'title' => $title,
            'description' => $description,
            'payload' => $payload,
            'currency' => $currency,
            'prices' => $prices,
            'business_connection_id' => $business_connection_id,
            'provider_token' => $provider_token,
            'subscription_period' => $subscription_period,
            'max_tip_amount' => $max_tip_amount,
            'suggested_tip_amounts' => $suggested_tip_amounts,
            'provider_data' => $provider_data,
            'photo_url' => $photo_url,
            'photo_size' => $photo_size,
            'photo_width' => $photo_width,
            'photo_height' => $photo_height,
            'need_name' => $need_name,
            'need_phone_number' => $need_phone_number,
            'need_email' => $need_email,
            'need_shipping_address' => $need_shipping_address,
            'send_phone_number_to_provider' => $send_phone_number_to_provider,
            'send_email_to_provider' => $send_email_to_provider,
            'is_flexible' => $is_flexible,
        ]);
    }

    public function answerShippingQuery(
        string $shipping_query_id,
        bool $ok,
        ?array $shipping_options = null,
        ?string $error_message = null,
    ): true {
        return $this->call('answerShippingQuery', [
            'shipping_query_id' => $shipping_query_id,
            'ok' => $ok,
            'shipping_options' => $shipping_options,
            'error_message' => $error_message,
        ]);
    }

    public function answerPreCheckoutQuery(
        string $pre_checkout_query_id,
        bool $ok,
        ?string $error_message = null,
    ): true {
        return $this->call('answerPreCheckoutQuery', [
            'pre_checkout_query_id' => $pre_checkout_query_id,
            'ok' => $ok,
            'error_message' => $error_message,
        ]);
    }

    public function getMyStarBalance(): StarAmount
    {
        return StarAmount::from($this->call('getMyStarBalance'));
    }

    public function getStarTransactions(?int $offset = null, ?int $limit = null): StarTransactions
    {
        return StarTransactions::from($this->call('getStarTransactions', [
            'offset' => $offset,
            'limit' => $limit,
        ]));
    }

    public function refundStarPayment(int $user_id, string $telegram_payment_charge_id): true
    {
        return $this->call('refundStarPayment', [
            'user_id' => $user_id,
            'telegram_payment_charge_id' => $telegram_payment_charge_id,
        ]);
    }

    public function editUserStarSubscription(
        int $user_id,
        string $telegram_payment_charge_id,
        bool $is_canceled,
    ): true {
        return $this->call('editUserStarSubscription', [
            'user_id' => $user_id,
            'telegram_payment_charge_id' => $telegram_payment_charge_id,
            'is_canceled' => $is_canceled,
        ]);
    }
}

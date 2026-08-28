<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Console\Output;

use Appto\TelegramBot\Bot\BotIdentity;
use Appto\TelegramBot\Client\TelegramClient;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\spin;

final readonly class WebhookPrompts
{
    public function __construct(private TelegramClient $client) {}

    public function ensureRemoved(): bool
    {
        $info = spin(fn () => $this->client->getWebhookInfo(), 'Checking webhook status...');

        if (empty($info->url)) {
            return true;
        }

        $confirmed = confirm(
            label: "Webhook is already set ({$info->url}). Remove it before starting long polling?",
            default: false,
            hint: 'Long polling cannot start while a webhook is set.',
        );

        if (! $confirmed) {
            return false;
        }

        spin(fn () => $this->client->deleteWebhook(), 'Removing webhook...');
        note('Webhook removed.');

        return true;
    }

    public function set(BotIdentity $identity): void
    {
        $existing = spin(fn () => $this->client->getWebhookInfo(), 'Checking webhook status...');
        $url = route('telegram.webhook', $identity->id);

        if (! empty($existing->url) && $existing->url !== $url) {
            $confirmed = confirm(
                label: "A different webhook is already set ({$existing->url}). Overwrite it?",
                default: false,
            );

            if (! $confirmed) {
                note('Webhook was not changed.');

                return;
            }
        }

        spin(fn () => $this->client->setWebhook($url, secret_token: $identity->webhook_secret), 'Setting webhook...');
        info("Webhook set to {$url}.");
    }

    public function remove(): void
    {
        $info = spin(fn () => $this->client->getWebhookInfo(), 'Checking webhook status...');

        if (empty($info->url)) {
            note('No webhook is set.');

            return;
        }

        spin(fn () => $this->client->deleteWebhook(), 'Removing webhook...');
        info('Webhook removed.');
    }
}

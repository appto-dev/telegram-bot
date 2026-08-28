<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client\Traits;

use Appto\TelegramBot\Type\InputFile;
use Appto\TelegramBot\Type\Update;
use Appto\TelegramBot\Type\WebhookInfo;

trait GettingUpdatesTrait
{
    public function getUpdates(
        ?int $offset = null,
        ?int $limit = null,
        ?int $timeout = null,
        ?array $allowed_updates = null,
    ): array {
        return Update::collect(
            $this->call('getUpdates', [
                'offset' => $offset,
                'limit' => $limit,
                'timeout' => $timeout,
                'allowed_updates' => $allowed_updates,
            ])
        );
    }

    public function setWebhook(
        string $url,
        ?InputFile $certificate = null,
        ?string $ip_address = null,
        ?int $max_connections = null,
        ?array $allowed_updates = null,
        ?bool $drop_pending_updates = null,
        ?string $secret_token = null,
    ): true {
        return $this->call('setWebhook', [
            'url' => $url,
            'certificate' => $certificate,
            'ip_address' => $ip_address,
            'max_connections' => $max_connections,
            'allowed_updates' => $allowed_updates,
            'drop_pending_updates' => $drop_pending_updates,
            'secret_token' => $secret_token,
        ]);
    }

    public function deleteWebhook(?bool $drop_pending_updates = null): true
    {
        return $this->call('deleteWebhook', [
            'drop_pending_updates' => $drop_pending_updates,
        ]);
    }

    public function getWebhookInfo(): WebhookInfo
    {
        return WebhookInfo::from($this->call('getWebhookInfo'));
    }
}

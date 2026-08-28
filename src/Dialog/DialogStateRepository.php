<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Dialog;

interface DialogStateRepository
{
    public function find(string $bot_id, int|string $chat_id, int $user_id): ?DialogState;

    public function isActive(string $bot_id, int|string $chat_id, int $user_id): bool;

    public function save(DialogState $state): void;

    public function delete(string $bot_id, int|string $chat_id, int $user_id): int;

    public function all(): ?array;
}

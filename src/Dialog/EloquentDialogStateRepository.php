<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Dialog;

use Illuminate\Database\Eloquent\Builder;

final class EloquentDialogStateRepository implements DialogStateRepository
{
    public function find(string $bot_id, int|string $chat_id, int $user_id): ?DialogState
    {
        $dialogState = $this->stateQuery($bot_id, $chat_id, $user_id)->first();
        if (! $dialogState) {
            return null;
        }

        return DialogState::from([
            'botName' => $dialogState->bot_name,
            'chatId' => $dialogState->chat_id,
            'userId' => $dialogState->user_id,
            'handler' => $dialogState->handler,
            'step' => $dialogState->step,
            'answers' => $dialogState->answers,
            'lastTouchedAt' => $dialogState->last_touched_at,
        ]);
    }

    public function isActive(string $bot_id, int|string $chat_id, int $user_id): bool
    {
        return $this->stateQuery($bot_id, $chat_id, $user_id)->exists();
    }

    public function save(DialogState $state): void
    {
        DialogStateModel::query()->updateOrCreate(
            ['bot_name' => $state->botName, 'chat_id' => $state->chatId, 'user_id' => $state->userId],
            [
                'handler' => $state->handler,
                'step' => $state->step,
                'answers' => $state->answers,
                'last_touched_at' => now(),
            ],
        );
    }

    public function delete(string $bot_id, int|string $chat_id, int $user_id): int
    {
        return $this->stateQuery($bot_id, $chat_id, $user_id)->delete();
    }

    public function all(): ?array
    {
        return DialogStateModel::all()->toArray() ?? null;
    }

    private function stateQuery(string $bot_id, int|string $chat_id, int $user_id): Builder
    {
        return DialogStateModel::query()
            ->where('bot_name', $bot_id)
            ->where('chat_id', $chat_id)
            ->where('user_id', $user_id);
    }
}

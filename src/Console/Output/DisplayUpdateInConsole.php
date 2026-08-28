<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Console\Output;

use Appto\TelegramBot\Bot\BotIdentity;
use Appto\TelegramBot\Dialog\DialogStateRepository;
use Appto\TelegramBot\Type\Message;
use Appto\TelegramBot\Type\Update;
use Appto\TelegramBot\Type\User;
use Appto\TelegramBot\Update\UpdateType;
use Illuminate\Console\OutputStyle;

final readonly class DisplayUpdateInConsole
{
    public function __construct(private OutputStyle $output, private BotIdentity $identity) {}

    public function handle(Update $update): void
    {
        $time = now()->format('H:i:s');
        [$icon, $summary] = $this->describe($update);

        $this->output->writeln(sprintf(
            '<fg=gray>[%s]</> %s <fg=cyan>#%d</> %s',
            $time,
            $icon,
            $update->update_id,
            $summary,
        ));

        if ($this->output->isVerbose()) {
            $this->output->writeln('<fg=gray>'.json_encode(
                $this->filterNulls($update->toArray()),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            ).'</>');
        }
    }

    /** @return array{0: string, 1: string} */
    private function describe(Update $update): array
    {
        return match (UpdateType::detect($update)) {
            UpdateType::MESSAGE => ['💬', $this->describeMessage($update->message)],

            UpdateType::EDITED_MESSAGE => ['✏️', 'edited: '.$this->describeMessage($update->edited_message)],

            UpdateType::CHANNEL_POST => ['📢', 'channel post: '.$this->describeMessage($update->channel_post)],

            UpdateType::EDITED_CHANNEL_POST => [
                '📢', 'edited channel post: '.$this->describeMessage($update->edited_channel_post),
            ],

            UpdateType::BUSINESS_MESSAGE => [
                '💼', 'business message: '.$this->describeMessage($update->business_message),
            ],

            UpdateType::EDITED_BUSINESS_MESSAGE => [
                '💼', 'edited business message: '.$this->describeMessage($update->edited_business_message),
            ],

            UpdateType::BUSINESS_CONNECTION => [
                '🔗',
                sprintf(
                    'business_connection <fg=cyan>%s</> is_enabled=%s',
                    $update->business_connection->id,
                    $update->business_connection->is_enabled ? 'true' : 'false',
                ),
            ],

            UpdateType::DELETED_BUSINESS_MESSAGES => [
                '🗑️',
                sprintf(
                    'deleted_business_messages in <fg=cyan>%d</>: %d msg(s)',
                    $update->deleted_business_messages->chat->id,
                    count($update->deleted_business_messages->message_ids),
                ),
            ],

            UpdateType::MESSAGE_REACTION => [
                '👍',
                sprintf(
                    'reaction from <fg=green>%s</> in <fg=cyan>%d</>',
                    $this->userLabel($update->message_reaction->user),
                    $update->message_reaction->chat->id,
                ),
            ],

            UpdateType::MESSAGE_REACTION_COUNT => [
                '📊',
                sprintf(
                    'reaction_count in <fg=cyan>%d</> msg=%d',
                    $update->message_reaction_count->chat->id,
                    $update->message_reaction_count->message_id
                ),
            ],

            UpdateType::INLINE_QUERY => [
                '🔍',
                sprintf(
                    'inline_query from <fg=green>%s</>: "%s"',
                    $this->userLabel($update->inline_query->from),
                    str($update->inline_query->query)->limit(60),
                ),
            ],

            UpdateType::CHOSEN_INLINE_RESULT => [
                '✅',
                sprintf(
                    'chosen_inline_result from <fg=green>%s</>: %s',
                    $this->userLabel($update->chosen_inline_result->from),
                    $update->chosen_inline_result->result_id
                ),
            ],

            UpdateType::CALLBACK_QUERY => [
                '🔘',
                sprintf(
                    'callback from <fg=green>%s</> %s data=<fg=yellow>%s</>',
                    $this->userLabel($update->callback_query->from),
                    $this->dialogLabel($update->callback_query->message->chat->id, $update->callback_query->from->id),
                    $update->callback_query->data ?? '—',
                ),
            ],

            UpdateType::SHIPPING_QUERY => [
                '📦',
                sprintf(
                    'shipping_query from <fg=green>%s</> id=%s',
                    $this->userLabel($update->shipping_query->from),
                    $update->shipping_query->id
                ),
            ],

            UpdateType::PRE_CHECKOUT_QUERY => [
                '💳',
                sprintf(
                    'pre_checkout_query from <fg=green>%s</> %s %s',
                    $this->userLabel($update->pre_checkout_query->from),
                    $update->pre_checkout_query->total_amount,
                    $update->pre_checkout_query->currency,
                ),
            ],

            UpdateType::PURCHASED_PAID_MEDIA => [
                '⭐',
                sprintf(
                    'purchased_paid_media from <fg=green>%s</>',
                    $this->userLabel($update->purchased_paid_media->from)
                ),
            ],

            UpdateType::POLL => [
                '📊',
                sprintf('poll "%s" (id=%s)', str($update->poll->question)->limit(60), $update->poll->id),
            ],

            UpdateType::POLL_ANSWER => [
                '🗳️',
                sprintf(
                    'poll_answer from <fg=green>%s</> id=%s',
                    $this->userLabel($update->poll_answer->user),
                    $update->poll_answer->poll_id
                ),
            ],

            UpdateType::MY_CHAT_MEMBER => [
                '👤',
                sprintf(
                    'my_chat_member in chat <fg=cyan>%d</>: %s → %s',
                    $update->my_chat_member->chat->id,
                    $update->my_chat_member->old_chat_member->status,
                    $update->my_chat_member->new_chat_member->status,
                ),
            ],

            UpdateType::CHAT_MEMBER => [
                '👥',
                sprintf(
                    'chat_member in chat <fg=cyan>%d</>: %s → %s',
                    $update->chat_member->chat->id,
                    $update->chat_member->old_chat_member->status,
                    $update->chat_member->new_chat_member->status,
                ),
            ],

            UpdateType::CHAT_JOIN_REQUEST => [
                '🚪',
                sprintf(
                    'chat_join_request from <fg=green>%s</> to <fg=cyan>%d</>',
                    $this->userLabel($update->chat_join_request->from),
                    $update->chat_join_request->chat->id
                ),
            ],

            UpdateType::CHAT_BOOST => [
                '🚀',
                sprintf(
                    'chat_boost in <fg=cyan>%d</> id=%s',
                    $update->chat_boost->chat->id,
                    $update->chat_boost->boost->boost_id
                ),
            ],

            UpdateType::REMOVED_CHAT_BOOST => [
                '🚀',
                sprintf(
                    'removed_chat_boost in <fg=cyan>%d</> id=%s',
                    $update->removed_chat_boost->chat->id,
                    $update->removed_chat_boost->boost_id
                ),
            ],

            // Нестандартные для Bot API типы — уточните реальные поля своих DTO
            UpdateType::GUEST_MESSAGE => ['👋', 'guest_message: '.$this->describeMessage($update->guest_message)],

            UpdateType::MANAGER_BOT => ['🤖', 'managed_bot event'],

            UpdateType::SUBSCRIPTION => ['💎', 'subscription event'],

            default => ['❓', 'unhandled update type'],
        };
    }

    private function describeMessage(Message $message): string
    {
        $from = $this->userLabel($message->from);
        $chat = $message->chat->type !== 'private'
            ? sprintf(' in <fg=magenta>%s</>', $message->chat->title ?? $message->chat->id)
            : '';

        $content = match (true) {
            $message->text !== null => sprintf('"%s"', str($message->text)->limit(80)),
            $message->photo !== null => sprintf('[photo] %s', $message->caption ?? ''),
            $message->document !== null => sprintf('[document: %s]', $message->document->fileName ?? '?'),
            $message->sticker !== null => '[sticker]',
            $message->poll !== null => sprintf('[poll] %s', $message->poll->question),

            default => '[unsupported content]',
        };

        return sprintf('from <fg=green>%s</>%s %s', $from, $chat, $this->dialogLabel($message->chat->id, $message->from->id).$content);
    }

    private function dialogLabel(int|string $chatId, int $userId): string
    {
        $repository = app(DialogStateRepository::class);
        $dialogState = $repository->find($this->identity->id, $chatId, $userId);

        return $dialogState
            ? sprintf('<fg=magenta>[🗨️ %s:%s]</> ', class_basename($dialogState->handler), $dialogState->step)
            : '';
    }

    private function userLabel(?User $user): string
    {
        if ($user === null) {
            return 'unknown';
        }

        return ($user->username !== null
            ? '@'.$user->username
            : trim($user->first_name.' '.($user->last_name ?? ''))
        ).' ['.$user->id.']';
    }

    /**
     * Рекурсивно проходит по Data-объекту (или массиву), удаляя null
     * из всех вложенных массивов через array_filter.
     */
    private function filterNulls(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->filterNulls($value);
            }
        }

        return array_filter($data);
    }
}

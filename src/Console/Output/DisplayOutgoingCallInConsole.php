<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Console\Output;

use Appto\TelegramBot\Events\TelegramApiCallMade;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

final readonly class DisplayOutgoingCallInConsole
{
    private const array RELEVANT = [
        'sendMessage', 'sendPhoto', 'sendLivePhoto', 'sendAudio', 'sendDocument',
        'sendVideo', 'sendAnimation', 'sendVoice', 'sendVideoNote', 'sendPaidMedia',
        'sendMediaGroup', 'sendLocation', 'sendVenue', 'sendContact', 'sendPoll',
        'sendChecklist', 'sendDice',
    ];

    private const array ICONS = [
        'sendMessage' => '💬', 'sendPhoto' => '🖼', 'sendLivePhoto' => '🖼',
        'sendAudio' => '🎵', 'sendDocument' => '📄', 'sendVideo' => '🎥',
        'sendAnimation' => '🎬', 'sendVoice' => '🎙', 'sendVideoNote' => '⭕',
        'sendPaidMedia' => '💰', 'sendMediaGroup' => '🖼', 'sendLocation' => '📍',
        'sendVenue' => '📌', 'sendContact' => '👤', 'sendPoll' => '📊',
        'sendChecklist' => '☑️', 'sendDice' => '🎲',
    ];

    // ключ, под которым Telegram отдаёт файл, для каждого media-типа
    private const array MEDIA_KEYS = [
        'photo' => 'photo', // особый случай — массив размеров, обрабатывается отдельно
        'document' => 'document',
        'video' => 'video',
        'audio' => 'audio',
        'voice' => 'voice',
        'video_note' => 'video_note',
        'animation' => 'animation',
    ];

    public function __construct(private OutputStyle $output) {}

    public function handle(TelegramApiCallMade $event): void
    {
        if (! in_array($event->method, self::RELEVANT, true) || ! is_array($event->response)) {
            return;
        }

        $content = $this->extractContent($event->method, $event->response);
        $keyboard = $this->extractKeyboard($event->response);

        $this->output->writeln(sprintf(
            '<fg=gray>[%s]</> 🤖 <fg=green>→</> <fg=gray>[%s]:</> "%s"%s',
            now()->format('H:i:s'),
            $event->method,
            $content,
            $keyboard !== null ? " <fg=gray>{$keyboard}</>" : '',
        ));
    }

    private function extractContent(string $method, array $response): string
    {
        $icon = self::ICONS[$method] ?? '💬';

        if ($method === 'sendMediaGroup') {
            $count = count($response);
            $caption = $response[0]['caption'] ?? null;

            return "{$icon} media group ({$count})".($caption !== null ? ': '.Str::limit($caption, 60) : '');
        }

        if (($fileId = $this->extractFileId($response)) !== null) {
            $caption = $response['caption'] ?? null;

            return sprintf(
                '%s <fg=gray>%s</>%s',
                $icon,
                $fileId,
                $caption !== null ? ' '.Str::limit($caption, 60) : '',
            );
        }

        return match (true) {
            isset($response['poll']) => sprintf(
                '%s %s <fg=gray>(%d options)</>',
                $icon,
                Str::limit($response['poll']['question'], 60),
                count($response['poll']['options']),
            ),
            isset($response['checklist']) => sprintf(
                '%s %s <fg=gray>(%d items)</>',
                $icon,
                Str::limit($response['checklist']['title'], 60),
                count($response['checklist']['tasks']),
            ),
            isset($response['dice']) => sprintf('%s %s (%d)', $icon, $response['dice']['emoji'], $response['dice']['value']),
            isset($response['venue']) => sprintf('%s %s', $icon, Str::limit($response['venue']['title'], 60)),
            isset($response['contact']) => sprintf('%s %s', $icon, $response['contact']['first_name']),
            isset($response['location']) => "{$icon} location",
            isset($response['text']) => Str::limit($response['text'], 80),
            isset($response['caption']) => sprintf('%s %s', $icon, Str::limit($response['caption'], 80)),
            default => "{$icon} sent",
        };
    }

    private function extractFileId(array $response): ?string
    {
        if (isset($response['photo'])) {
            return Arr::last($response['photo'])['file_id'] ?? null;
        }

        foreach (self::MEDIA_KEYS as $key) {
            if ($key === 'photo') {
                continue;
            }

            if (isset($response[$key]['file_id'])) {
                return $response[$key]['file_id'];
            }
        }

        return null;
    }

    private function extractKeyboard(array $response): ?string
    {
        $markup = $response['reply_markup'] ?? null;

        if ($markup === null) {
            return null;
        }

        if (! empty($markup['inline_keyboard'])) {
            $count = array_sum(array_map('count', $markup['inline_keyboard']));

            return "⌨ inline({$count})";
        }

        if (! empty($markup['keyboard'])) {
            $count = array_sum(array_map('count', $markup['keyboard']));

            return "⌨ reply({$count})";
        }

        return null;
    }
}

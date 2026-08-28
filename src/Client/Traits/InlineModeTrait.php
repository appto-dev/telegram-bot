<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client\Traits;

use Appto\TelegramBot\Type\InlineQueryResultsButton;

trait InlineModeTrait
{
    public function answerInlineQuery(
        string $inline_query_id,
        array $results,
        ?int $cache_time = null,
        ?bool $is_personal = null,
        ?string $next_offset = null,
        ?InlineQueryResultsButton $button = null,
    ): true {
        return $this->call('answerInlineQuery', [
            'inline_query_id' => $inline_query_id,
            'results' => $results,
            'cache_time' => $cache_time,
            'is_personal' => $is_personal,
            'next_offset' => $next_offset,
            'button' => $button,
        ]);
    }
}

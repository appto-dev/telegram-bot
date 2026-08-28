<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Dialog;

use Illuminate\Database\Eloquent\Model;

final class DialogStateModel extends Model
{
    protected $table = 'telegram_dialog_states';

    protected $fillable = [
        'bot_name',
        'chat_id',
        'user_id',
        'handler',
        'step',
        'answers',
        'last_touched_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'last_touched_at' => 'immutable_datetime',
    ];
}

<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Bot;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $token
 * @property string $webhook_secret
 * @property string $handler
 * @property bool $is_active
 */
final class TelegramBotModel extends Model
{
    protected $table = 'telegram_bots';

    protected $fillable = ['name', 'token', 'webhook_secret', 'handler', 'is_active'];

    protected $casts = [
        'token' => 'encrypted',
        'webhook_secret' => 'encrypted',
        'is_active' => 'boolean',
    ];
}

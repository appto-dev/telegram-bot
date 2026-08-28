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

    protected $fillable = ['name', 'token', 'handler'];

    protected $casts = [
        'token' => 'encrypted',
        'webhook_secret' => 'encrypted',
        'is_active' => 'boolean',
    ];

    public function resetWebhookSecret(): void
    {
        $this->webhook_secret = $this->generateSecretString();
        $this->save();
    }

    public function generateSecretString(): string
    {
        return bin2hex(random_bytes(32));
    }

    protected static function boot(): void
    {
        parent::boot();

        parent::creating(function (self $model) {
            if (empty($model->webhook_secret)) {
                $model->webhook_secret = $model->generateSecretString();
            }
        });
    }
}

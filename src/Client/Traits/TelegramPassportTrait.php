<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client\Traits;

trait TelegramPassportTrait
{
    public function setPassportDataErrors(int $user_id, array $errors): true
    {
        return $this->call('setPassportDataErrors', [
            'user_id' => $user_id,
            'errors' => $errors,
        ]);
    }
}

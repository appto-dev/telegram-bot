<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Contracts;

use Appto\TelegramBot\Update\UpdateContext;

interface RequiresPermission
{
    /**
     * Determine if the user is authorized to perform the action.
     */
    public function authorize(UpdateContext $context): bool;
}

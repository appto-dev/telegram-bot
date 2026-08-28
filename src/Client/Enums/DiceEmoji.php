<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client\Enums;

enum DiceEmoji: string
{
    case DICE = '🎲';
    case DARTS = '🎯';
    case BASKETBALL = '🏀';
    case FOOTBALL = '⚽';
    case BOWLING = '🎳';
    case SLOT_MACHINE = '🎰';

    public function minValue(): int
    {
        return 1;
    }

    public function maxValue(): int
    {
        return match ($this) {
            self::DICE, self::DARTS, self::BOWLING => 6,
            self::BASKETBALL, self::FOOTBALL => 5,
            self::SLOT_MACHINE => 64,
        };
    }
}

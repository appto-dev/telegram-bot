<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Client;

use Appto\TelegramBot\Client\Traits\AvailableMethodsTrait;
use Appto\TelegramBot\Client\Traits\GamesTrait;
use Appto\TelegramBot\Client\Traits\GettingUpdatesTrait;
use Appto\TelegramBot\Client\Traits\InlineModeTrait;
use Appto\TelegramBot\Client\Traits\PaymentsTrait;
use Appto\TelegramBot\Client\Traits\RichMessagesTrait;
use Appto\TelegramBot\Client\Traits\StickersTrait;
use Appto\TelegramBot\Client\Traits\TelegramPassportTrait;
use Appto\TelegramBot\Client\Traits\UpdatingMessagesTrait;
use Appto\TelegramBot\Support\AvailableMethods;
use Appto\TelegramBot\Support\Games;
use Appto\TelegramBot\Support\GettingUpdates;
use Appto\TelegramBot\Support\InlineMode;
use Appto\TelegramBot\Support\Payments;
use Appto\TelegramBot\Support\RichMessages;
use Appto\TelegramBot\Support\Stickers;
use Appto\TelegramBot\Support\TelegramPassport;
use Appto\TelegramBot\Support\UpdatingMessages;

/**
 * Telegram Bot API Client.
 *
 * This client implements the latest Telegram Bot API version 10.3 (released August 24, 2026).
 * It provides a complete interface to interact with the Telegram Bot API, including:
 * - <a href="https://core.telegram.org/bots/api#getting-updates">Getting updates</a> from the bot
 * - <a href="https://core.telegram.org/bots/api#available-methods">Available methods</a> for bot operations
 * - <a href="https://core.telegram.org/bots/api#updating-messages">Updating and editing messages</a>
 * - <a href="https://core.telegram.org/bots/api#stickers">Stickers</a> management
 * - <a href="https://core.telegram.org/bots/api#rich-messages">Rich messages</a> (photos, videos, documents, etc.)
 * - <a href="https://core.telegram.org/bots/api#inline-mode">Inline mode</a> support
 * - <a href="https://core.telegram.org/bots/api#payments">Payments</a> integration
 * - <a href="https://core.telegram.org/bots/api#telegram-passport">Telegram Passport</a>
 * - <a href="https://core.telegram.org/bots/api#games">Games</a> platform
 */
final class TelegramClient extends BaseClient implements AvailableMethods, Games, GettingUpdates, InlineMode, Payments, RichMessages, Stickers, TelegramPassport, UpdatingMessages
{
    use AvailableMethodsTrait;
    use GamesTrait;
    use GettingUpdatesTrait;
    use InlineModeTrait;
    use PaymentsTrait;
    use RichMessagesTrait;
    use StickersTrait;
    use TelegramPassportTrait;
    use UpdatingMessagesTrait;
}

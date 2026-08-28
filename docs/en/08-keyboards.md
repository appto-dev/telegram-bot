# 8. Keyboards

Keyboard layouts are plain Bot API DTOs (from `appto-team/telegram-bot-cast-laravel`), not a
framework-specific "builder". Created via `X::from([...])`.

## 8.1 Reply keyboard (buttons under the input field)

```php
use Appto\TelegramBot\Type\KeyboardButton;
use Appto\TelegramBot\Type\ReplyKeyboardMarkup;

ReplyKeyboardMarkup::from([
    'keyboard' => [
        [
            KeyboardButton::from(['text' => '♻️ Catalog']),
            KeyboardButton::from(['text' => '⚙️ My cabinet']),
        ],
    ],
    'is_persistent' => true,
    'resize_keyboard' => true,
]);
```

Tapping such a button arrives as a plain text message — handled via `onText()` (see
[7. Plain text and triggers](07-text-and-buttons.md)).

## 8.2 Inline keyboard (buttons under the message)

```php
use Appto\TelegramBot\Type\InlineKeyboardButton;
use Appto\TelegramBot\Type\InlineKeyboardMarkup;

InlineKeyboardMarkup::from([
    'inline_keyboard' => [
        [
            InlineKeyboardButton::from([
                'text' => 'Confirm',
                'callback_data' => 'order:confirm 42',
            ]),
        ],
    ],
]);
```

Tapping arrives as a `callback_query` — handled via `onCallback()` (see
[6. Buttons and callback queries](06-callbacks.md)). The `callback_data` value must match the
pattern you registered in `onCallback()`.

Passed when sending a message:

```php
$context->reply('Please confirm the order', reply_markup: $keyboard);
```

## 8.3 Removing the keyboard / forcing a reply

```php
use Appto\TelegramBot\Type\ReplyKeyboardRemove;
use Appto\TelegramBot\Type\ForceReply;

$context->reply('Keyboard hidden', reply_markup: ReplyKeyboardRemove::from(['remove_keyboard' => true]));
$context->reply('Reply to this message', reply_markup: ForceReply::from(['force_reply' => true]));
```

## 8.4 Recommendation — extract to its own class

Like any other layout, a keyboard that grows should live in its own class next to the button-text
constants (example in
[7.3](07-text-and-buttons.md#73-example-with-a-reply-keyboard)). That gives you a single source of
truth: change a button's label and there's nothing else to keep in sync.

## Next

→ [9. Replying to users](09-replies.md)

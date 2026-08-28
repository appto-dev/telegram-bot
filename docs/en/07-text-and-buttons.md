# 7. Plain text and triggers

## 7.1 Exact text match

```php
$this->onText('hello', HelloHandler::class);
```

`onText()` only fires on an **exact** match between the message text and the given string —
case-insensitive, and ignoring leading/trailing whitespace (`" Hello "` matches `'hello'`). It's
still not a substring search or a regex: if the user types anything extra (e.g. `hello!`), the
handler won't run. If the message has no text but does have a media caption (photo, document), the
caption is compared instead.

## 7.2 When this fits

`onText()` is primarily meant for reply-keyboard buttons (buttons under the input field) — their
text is known upfront and always matches exactly, because the user taps the button rather than
typing it themselves. Using `onText()` to parse arbitrary user phrases isn't a good fit — for free
text, a command with an argument or a dialog step (see [10. Dialogs](10-dialogs.md)) is a better
tool.

## 7.3 Example with a reply keyboard

The trigger text is usually kept next to the keyboard layout itself, so the button label and the
`onText()` string never drift apart:

```php
class GlobalReplyMarkup
{
    public const string CABINET_LABEL_TRIGGER = '⚙️ My cabinet';

    public static function make(): ReplyKeyboardMarkup
    {
        return ReplyKeyboardMarkup::from([
            'keyboard' => [[
                KeyboardButton::from(['text' => self::CABINET_LABEL_TRIGGER]),
            ]],
            'resize_keyboard' => true,
        ]);
    }
}
```

```php
$this->onText(GlobalReplyMarkup::CABINET_LABEL_TRIGGER, CabinetCommand::class);
```

This way the button label exists in exactly one place in the project — a constant — used both to
build the keyboard and to register the trigger.

## Next

→ [8. Keyboards](08-keyboards.md)

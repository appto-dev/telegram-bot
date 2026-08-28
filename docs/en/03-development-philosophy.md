# 3. How bot development works

## 3.1 One bot — one class

A bot is a subclass of the abstract `Bot` class. The only required method is `boot()`: this is
where — and only where — everything the bot can do gets registered.

```php
namespace App\ShopBot;

use Appto\TelegramBot\Bot\Bot;

class ShopBot extends Bot
{
    protected function boot(): void
    {
        // register commands, buttons, text triggers, middleware
    }
}
```

The `Bot` class itself shouldn't grow business logic — if `if`/`match` blocks with reply logic
start creeping into `boot()`, that's a sign to extract them into a separate handler class.

## 3.2 Rule: "one task — one class"

Every unit of behavior is a small, dedicated class:

| What | Interface | Where |
|---|---|---|
| Command (`/start`) | `CommandHandler` | `Commands/` |
| Callback button | `CallbackHandler` | `Callbacks/` |
| Multi-step scenario | `Dialog` (abstract) | `Dialogs/<Scenario>/` |
| A single dialog step | `Step` | `Dialogs/<Scenario>/Steps/` |
| Keyboard layout | — (a plain class) | `Keyboards/` |
| Cross-cutting check | `UpdateMiddleware` | `Middleware/` |

This layout isn't enforced by the framework (it doesn't care where your files live) — it's the
convention followed by the example package (`app/ShopBot`). It pays off by keeping a bot's
`boot()` short and readable, and every class independently testable.

## 3.3 One update's journey, in plain terms

1. A new message/button tap arrives from Telegram.
2. If the user already has a dialog in progress in this chat (they're mid-way through a multi-step
   scenario) — the update goes straight to that dialog's current step, bypassing everything else.
   The exception is a command: any command typed mid-dialog cancels it automatically (`onCancel()`
   runs), and the command is then processed normally.
3. Otherwise the framework checks, in order: is it a command? does the text match a registered
   callback pattern? does the text match one of the `onText()` triggers exactly? does the update
   type match an `onUpdate()` registration?
4. As soon as a match is found — your class runs, and the update is considered handled.
5. If nothing matched — the bot's optional `fallback()` is called (does nothing by default).

## 3.4 What to use: a command, a button, or a dialog?

| Scenario | Tool |
|---|---|
| A one-off action with no input needed (`/start`, `/help`, show balance) | Command (`onCommand`) |
| Reacting to an inline-button tap on an already-sent message | Callback (`onCallback`) |
| Reacting to a reply-keyboard button (button under the input field) | Text trigger (`onText`) |
| You need to ask for several things in sequence (name → email → confirmation) | Dialog |
| Handling a "raw" update type with no ready-made hook (e.g. `poll_answer`) | `onUpdate()` |

If you're unsure between "one command with an argument" and "a dialog", the rule of thumb is
simple: use a dialog when the user's answer needs to be remembered *across messages*. If everything
fits into a single message (a command with arguments, a button with `callback_data`), you don't
need a dialog.

## 3.5 Recommended folder structure

Based on `app/ShopBot`:

```
app/ShopBot/
├── ShopBot.php              — the bot itself, boot() only
├── Commands/
│   ├── StartCommand.php
│   └── CabinetCommand.php
├── Keyboards/
│   └── GlobalReplyMarkup.php
├── Middleware/
│   └── CaptchaMiddleware.php
└── Dialogs/
    └── Registration/
        ├── RegistrationDialog.php
        └── Steps/
            ├── AskName.php
            ├── AskEmail.php
            └── AskGender.php
```

For several bots in one application — a separate folder per bot (`app/ShopBot`, `app/SupportBot`,
…), without sharing handler classes between them: different bots usually have different business
logic even when a scenario sounds similar.

## 3.6 The philosophy behind replies

Inside a handler/step you have an update context object that lets you reply without manually
passing `chat_id` — the framework already knows who and where to reply to:

```php
public function handle(UpdateContext $context): void
{
    $context->reply('Done!');
}
```

This is deliberate: `chat_id`, and for business messages the `business_connection_id` too, are
inferred from the current update — you almost never need to pass them yourself. More in
[9. Replying to users](09-replies.md).

## Next

→ [4. Quick start](04-quick-start.md)

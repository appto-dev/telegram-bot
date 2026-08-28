# 1. Introduction

## 1.1 What this is

**Appto Telegram Bot Framework** (`appto-team/telegram-bot`) is a Laravel package for building one
or several Telegram bots inside a regular Laravel application: declarative registration of
commands, buttons and text triggers, a step-by-step dialog engine, update delivery via webhook or
long polling, built-in authorization and `/help`.

The package doesn't replace the Bot API — it gives you a convenient layer on top of it: you
describe *what* should happen in response to a command/button/message, and the framework takes
care of routing, dialog state and delivery.

## 1.2 The core idea

One bot class = one bot. Everything a bot can do — commands, buttons, dialogs — is registered in
a single place (the `boot()` method of that class), while the actual logic of every
command/button/step lives in its own small class:

```php
final class ShopBot extends Bot
{
    protected function boot(): void
    {
        $this->onCommand('start', StartCommand::class);
        $this->onCommand('help', HelpCommand::class);
        $this->onText(GlobalReplyMarkup::CABINET_LABEL_TRIGGER, CabinetCommand::class);
        $this->onCallback('order:confirm {id}', OrderConfirmHandler::class);
    }
}
```

Reading any bot's `boot()` should tell you its whole feature set in 10 seconds — that's a
deliberate design goal: a bot's configuration should read like a table of contents, not like code.

## 1.3 What the framework handles — and what's left to you

The framework is responsible for:

- routing an update to the right class (by command, `callback_data`, exact text, or update type);
- multi-step dialog state between messages;
- delivering updates to the bot (webhook or long polling for development);
- checking permissions before running a handler, if you declared them;
- assembling `/help` from command descriptions;
- convenient "reply in the current chat" methods without manually passing `chat_id`.

You're responsible for:

- the business logic inside each handler/step;
- keyboard layouts for your UX;
- your application's models and storage (the package itself only stores the bot list and active
  dialog state).

## 1.4 Requirements

- PHP 8.3+
- Laravel 13+
- `appto-team/telegram-bot-cast-laravel` — Bot API types and interfaces (installed automatically
  as a dependency)

## Next

→ [2. Installation](02-installation.md)

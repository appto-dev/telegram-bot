# 14. Multiple bots in one application

## 14.1 Registering a second bot

One project can serve any number of bots — just add another entry to the config:

```php
'bots' => [
    'default' => [
        'token' => env('DEFAULT_BOT_TOKEN'),
        'webhook_secret' => env('DEFAULT_BOT_WEBHOOK_SECRET'),
        'handler' => \App\MyBot\MyBot::class,
    ],
    'support' => [
        'token' => env('SUPPORT_BOT_TOKEN'),
        'webhook_secret' => env('SUPPORT_BOT_WEBHOOK_SECRET'),
        'handler' => \App\SupportBot\SupportBot::class,
    ],
],
```

Each bot is its own `Bot` subclass, usually in its own `app/` folder (see
[3.5](03-development-philosophy.md#35-recommended-folder-structure)).

## 14.2 Isolation between bots

Bots are fully independent of each other:

- each has its own token and its own set of commands/buttons/dialogs — they never collide, even if
  command names are identical;
- dialog state is scoped to a specific (bot, chat, user) triple — the same person can be mid-dialog
  in one bot and not in a dialog at all in another, at the same time;
- each bot has its own webhook route (`/telegram/webhook/default`, `/telegram/webhook/support`), and
  artisan commands take the bot's alias as their first argument (`telegram:poll default`,
  `telegram:routes support`).

## Next

→ [15. Bot source](15-bot-source.md)

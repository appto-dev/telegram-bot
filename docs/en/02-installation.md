# 2. Installation

## 2.1 Composer

```bash
composer require appto-team/telegram-bot
php artisan vendor:publish --tag=telegram-bot-config
php artisan migrate
```

Publish only what you need:

```bash
php artisan vendor:publish --tag=telegram-bot-config       # config only
php artisan vendor:publish --tag=telegram-bot-migrations   # migrations only
php artisan vendor:publish --tag=telegram-bot-lang         # translations only
```

Migrations create the dialog-state table (`telegram_dialog_states`). The `telegram_bots` table
(for storing bots in the database, see [15. Bot source](15-bot-source.md)) is published separately
and isn't needed by default if you keep your bots in the config file.

## 2.2 Environment variables

Minimum for one bot:

```env
TELEGRAM_BOT_TOKEN=123456:AA...
TELEGRAM_BOT_WEBHOOK_SECRET=any-random-string
```

Useful but optional:

```env
TELEGRAM_BOT_REPOSITORY=config        # or database, see §15
TELEGRAM_API_BASE_URI=https://api.telegram.org   # only change for a local Bot API Server
TELEGRAM_BOT_UNAUTHORIZED_MESSAGE=    # see §11 "Permissions"
```

## 2.3 Minimal config

After publishing, `config/telegram-bot.php` already ships a working skeleton — you just add your
bot:

```php
'bots' => [
    'shop' => [
        'token' => env('TELEGRAM_BOT_TOKEN'),
        'webhook_secret' => env('TELEGRAM_BOT_WEBHOOK_SECRET'),
        'bot' => \App\ShopBot\ShopBot::class,
    ],
],
```

The array key (`shop`) is an arbitrary bot alias — it's used in the webhook route and in every
artisan command (`telegram:poll shop`, `telegram:routes shop`, …).

## 2.4 First check

You still need a real bot token from [@BotFather](https://t.me/BotFather). Once it's in `.env`,
the fastest way to check everything is wired up correctly is:

```bash
php artisan telegram:poll shop
```

This starts receiving updates via long polling — no public HTTPS domain needed for local
development. See [13. Webhook and long polling](13-delivery.md) for more.

## Next

→ [3. How bot development works](03-development-philosophy.md)

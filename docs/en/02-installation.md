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

> ⚠️ **`TELEGRAM_BOT_TOKEN` and `TELEGRAM_BOT_WEBHOOK_SECRET` are confidential.** Never commit
> them to git, paste them into issues/chats/screenshots, or hand them to third parties. The token
> grants full control of the bot (sending messages as it, reading updates, changing the webhook),
> and the webhook secret is what protects the endpoint from forged requests — if either leaks,
> rotate it immediately (the token via @BotFather, the secret with the command from §2.2 above)
> and update it everywhere it's deployed.

Useful but optional:

```env
TELEGRAM_BOT_REPOSITORY=config        # or database, see §15
TELEGRAM_API_BASE_URI=https://api.telegram.org   # only change for a local Bot API Server
TELEGRAM_BOT_UNAUTHORIZED_MESSAGE=    # see §11 "Permissions"
```

You don't have to make up a random string for `TELEGRAM_BOT_WEBHOOK_SECRET` by hand — the package
can generate one for you:

```bash
php artisan telegram:webhook-secret
```

The command prints the generated string to the terminal (48 hex characters,
`bin2hex(random_bytes(24))`) — copy it into `.env`. It doesn't send anything or touch the file, it
only generates the value.

The same algorithm is available from code too —
`Appto\TelegramBot\Support\SecretGenerator::generate()`. The package no longer auto-generates a
`webhook_secret` when a `telegram_bots` row is created (`repository = database`) — if you need one
right away when creating a bot programmatically (e.g. in your own SaaS bot-onboarding flow), call
the generator yourself:

```php
use Appto\TelegramBot\Bot\TelegramBotModel;
use Appto\TelegramBot\Support\SecretGenerator;

TelegramBotModel::create([
    'name' => 'shop',
    'token' => $token,
    'handler' => \App\MyBot\MyBot::class,
    'webhook_secret' => SecretGenerator::generate(),
]);
```

`generate(int $bytes = 24)` takes an optional argument for the secret's length in bytes (the
resulting hex string is twice that length).

## 2.3 Minimal config

After publishing, `config/telegram-bot.php` already ships a working skeleton — you just add your
bot:

```php
'bots' => [
    'default' => [
        'token' => env('TELEGRAM_BOT_TOKEN'),
        'webhook_secret' => env('TELEGRAM_BOT_WEBHOOK_SECRET'),
        'handler' => \App\MyBot\MyBot::class,
    ],
],
```

The array key (`default`) is an arbitrary bot alias — it's used in the webhook route and in every
artisan command (`telegram:poll default`, `telegram:routes default`, …).

## 2.4 First check

You still need a real bot token from [@BotFather](https://t.me/BotFather). Once it's in `.env`,
the fastest way to check everything is wired up correctly is:

```bash
php artisan telegram:poll default
```

This starts receiving updates via long polling — no public HTTPS domain needed for local
development. See [13. Webhook and long polling](13-delivery.md) for more.

## Next

→ [3. How bot development works](03-development-philosophy.md)

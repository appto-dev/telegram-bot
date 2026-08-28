# 15. Where the bot list comes from

## 15.1 The simple way — config (default)

```env
TELEGRAM_BOT_REPOSITORY=config
```

The bot list is a static array in `config/telegram-bot.php` (`bots`). This fits the vast majority
of projects: bots are known ahead of time and added by a developer through code and deployment.

## 15.2 The flexible way — database

```env
TELEGRAM_BOT_REPOSITORY=database
```

The bot list is stored in a database table (the token is encrypted at rest). This makes sense when
bots are created dynamically — for example, if your application is itself a SaaS platform and each
customer connects their own bot through a UI, without a developer or a deploy involved.

Publish and run the migration before switching:

```bash
php artisan vendor:publish --tag=telegram-bot-migrations
php artisan migrate
```

## 15.3 Switching between them

The change is limited to a single environment variable, `TELEGRAM_BOT_REPOSITORY` — your bot code
(the `Bot` subclasses, handlers, dialogs) doesn't depend on the bot list's source at all and needs
no changes.

## Next

→ [16. Debugging](16-debugging.md)

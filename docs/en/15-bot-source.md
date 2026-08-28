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

## 15.4 Moving bots from config into the database

If bots are already described in `config/telegram-bot.php` (`bots`) and you're switching to
`database`, you don't need to re-enter them by hand — there's a command for that:

```bash
php artisan telegram:migrate-bots-to-database [--force] [--dry-run]
```

It reads `config('telegram-bot.bots')` and creates/updates the matching rows in `telegram_bots`
(keyed by `name` = the bot's alias in the config). Without flags it leaves bots that already exist
in the database alone and marks them `skipped`; `--force` overwrites them with the config's data;
`--dry-run` shows what would happen without writing anything. After migrating, switch
`TELEGRAM_BOT_REPOSITORY=database` (see 15.3) — you can leave the old `bots` entry in the config,
it's simply no longer read as the source.

## Next

→ [16. Debugging](16-debugging.md)

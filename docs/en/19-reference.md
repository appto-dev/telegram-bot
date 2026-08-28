# 19. Reference

## 19.1 Config keys (`config/telegram-bot.php`)

| Key | Purpose |
|---|---|
| `bots` | The bot list when `repository = config`: alias → `token`, `webhook_secret`, `bot` (class) |
| `repository` | Where the bot list comes from: `config` or `database` (env `TELEGRAM_BOT_REPOSITORY`) |
| `base_uri` | Base Bot API address (only change for a local Bot API Server) |
| `http` | HTTP client options (Guzzle `RequestOptions`) — timeouts, etc. |
| `unauthorized.message` | Default message on access denial (env `TELEGRAM_BOT_UNAUTHORIZED_MESSAGE`) |
| `unauthorized.show_alert` | Whether the denial shows as an alert for callback queries |

## 19.2 Artisan commands

| Command | Purpose |
|---|---|
| `telegram:poll {bot?}` | Long polling for development. Flags: `--timeout`, `-o/--show-outgoing`, `--only=*`, `--user=*`, `--dry-run`, `-l/--log-traffic`, `-v` — print the full JSON of every update (standard Console verbosity, see [16.4](16-debugging.md#164-verbose-update-output--v)) |
| `telegram:set-webhook {bot}` | Set the webhook |
| `telegram:delete-webhook {bot}` | Remove the webhook |
| `telegram:routes {bot?} [--type=commands\|callbacks\|text]` | List registered routes |

## 19.3 Glossary

- **Command** — a message like `/name`, handled via `onCommand()`.
- **Callback** — an inline-button tap, arrives as a `callback_query`, handled via `onCallback()`.
- **Text trigger** — an exact message-text match, handled via `onText()`.
- **Dialog** — a multi-step scenario with state kept between messages.
- **Step** — a single ask-and-answer unit inside a dialog.
- **Middleware** — code that runs for every update a bot receives, before routing.
- **Router** — the internal mechanism that matches an update to a registered handler (by
  command/callback pattern/text).
- **Update context (`UpdateContext`)** — the object carrying the incoming update's data plus
  `reply*()` methods for replying in the current chat.

## See also

- [1. Introduction](01-introduction.md)
- [3. How bot development works](03-development-philosophy.md)
- [17. Recipes](17-recipes.md)

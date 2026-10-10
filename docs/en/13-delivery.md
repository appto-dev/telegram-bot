# 13. Webhook and long polling

Telegram supports two ways of delivering updates to a bot, and both are implemented in the
framework — you don't need to switch code between them, they share the same processing pipeline.

## 13.1 The difference

| | Long polling | Webhook |
|---|---|---|
| How updates arrive | The bot keeps asking Telegram "any new updates?" | Telegram sends a POST request to your URL |
| Needs a public HTTPS URL | No | Yes |
| Best for | Local development | Production |
| How it runs | `php artisan telegram:poll` — keeps a process open | A regular HTTP route, no dedicated process needed |

## 13.2 Long polling for development

```bash
php artisan telegram:poll default
```

Without an argument, the command lets you pick a bot interactively:

```bash
php artisan telegram:poll
```

Before it starts, the command checks whether a webhook is already set for the bot (Telegram only
allows one delivery method at a time) and, if so, offers to remove it — no need to run
`telegram:delete-webhook` by hand first.

Useful flags:

| Flag | What it does |
|---|---|
| `--timeout=30` | Long-poll timeout in seconds, passed to Telegram |
| `-o`, `--show-outgoing` | Also print the bot's outgoing API calls to the console, not just incoming updates |
| `--only=message --only=callback_query` | Only show/dispatch the listed update types |
| `--user=123456789` | Only updates from the given Telegram user id |
| `--dry-run` | Show incoming updates but **don't** dispatch them to the bot's handlers — inspect traffic without side effects |
| `-l`, `--log-traffic` | Log raw incoming/outgoing payloads to `storage/logs/telegram-traffic.log` |
| `-v`, `--verbose` | Standard Artisan/Symfony verbosity flag (not declared in `$signature`): prints the full update JSON (nulls stripped) below each update line |

```bash
php artisan telegram:poll default --only=callback_query --user=123456789 --dry-run
```

Errors during polling don't stop the command: a failed `getUpdates` call backs off exponentially
(5s, 10s, 20s… capped at 60s) and retries; an unhandled exception inside a specific handler is
logged and doesn't interrupt processing of subsequent updates.

Long polling holds an open foreground process and isn't meant to run behind a process manager in
production.

## 13.3 Webhook for production

```bash
php artisan telegram:set-webhook default
php artisan telegram:delete-webhook default
```

Every bot gets its own route: `POST /api/telegram/webhook/{botName}`. Requests are authenticated with a
secret token Telegram sends in a header — it's derived deterministically from the app's `APP_KEY`
and the bot's token, so it can't be forged without knowing `APP_KEY`. There's nothing extra to
store or sync between environments — if `APP_KEY` or the bot token changes, the secret is
recomputed automatically; you just re-set the webhook.

`telegram:set-webhook` builds the URL from `APP_URL` by default. If Telegram must reach the app on
another domain — a tunnel during development, a separate public host — set the base address:

```env
TELEGRAM_WEBHOOK_URL=https://abc.ngrok.app
```

The route path is appended to it, so the bot `default` gets
`https://abc.ngrok.app/api/telegram/webhook/default`. `APP_URL` and the rest of the app's URLs are
unaffected. The route is named `api.telegram.webhook` if you need it in your own code.

## 13.4 Which one to pick

Develop locally with `telegram:poll` (no tunnel/HTTPS needed), deploy to production with a webhook
(`telegram:set-webhook`). You can't run both for the same bot at once — Telegram disables one when
the other is enabled, which is exactly why `telegram:poll` warns you about it upfront.

## Next

→ [14. Multiple bots](14-multiple-bots.md)

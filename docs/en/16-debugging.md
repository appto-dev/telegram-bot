# 16. Debugging

## 16.1 Listing every route a bot has

```bash
php artisan telegram:routes default
php artisan telegram:routes default --type=commands
```

Shows every registered command, callback pattern and text trigger for a given bot, and flags which
ones require authorization (`RequiresPermission`). Without a bot argument, it lists routes for
every registered bot at once.

This is the fastest way to check for a typo in a pattern or confirm a handler is actually
registered, without reading the whole bot class.

## 16.2 Inspecting traffic without running handlers

```bash
php artisan telegram:poll default --dry-run
```

Shows every incoming update in real time but doesn't dispatch them to the bot's handlers — handy
when you need to see exactly what Telegram is sending (e.g. figuring out a third-party client's
`callback_data` shape) without risking triggering a dialog or changing data.

## 16.3 Logging traffic to a file

```bash
php artisan telegram:poll default -l
```

Writes raw incoming and outgoing payloads to `storage/logs/telegram-traffic.log` — useful when a
bug doesn't reproduce clearly in the console and you need to attach a log to an issue, or review it
later.

## 16.4 Verbose update output: `-v`

```bash
php artisan telegram:poll default -v
```

By default `telegram:poll` prints one line per update (timestamp, type, a short summary). `-v` is
the standard Symfony/Laravel Console verbosity flag — it isn't declared in the command's
`$signature`, it's inherited from the base `Illuminate\Console\Command` — but the command does act
on it: with `-v`, the full update gets printed under each line too, as `Update::toArray()` rendered
with `JSON_PRETTY_PRINT`, with `null` fields filtered out for brevity. Handy when the summary line
isn't enough — e.g. inspecting every field of a nested object (`message.entities`, the whole
`callback_query.message`, etc.) without adding a `dd()` to the code or turning on persistent
logging via `-l`.

It combines with the other flags as usual — `telegram:poll default -v --dry-run` shows the full
JSON of incoming updates without dispatching them to the bot's handlers at all.

## 16.5 "The bot isn't responding" checklist

1. Are webhook and `telegram:poll` running for the same bot at the same time? Telegram only
   delivers updates one way at a time, so the other one goes silent (see
   [13.4](13-delivery.md#134-which-one-to-pick)).
2. For a webhook — is the URL reachable over HTTPS, does it return 200, is
   `VerifyWebhookSecretMiddleware` rejecting the request because the secret doesn't match (check
   `APP_KEY` and the bot token — the secret is derived deterministically from both)?
3. Is the handler you expect actually registered? — `php artisan telegram:routes {bot}`.
4. Does this user have an active dialog? During a dialog, updates go to the current step, not to
   the regular routers (see
   [10.3](10-dialogs.md#103-registering-a-dialog)); if the step doesn't reply, look for the bug in
   `Step::handle()`.
5. Did the bot's `middleware()` silently swallow the update by not calling `$next()` (see
   [12. Middleware](12-middleware.md))?
6. Did `RequiresPermission::authorize()` return `false` without `HasUnauthorizedMessage` — from the
   outside, that looks exactly like the bot going completely silent.

## Next

→ [17. Recipes](17-recipes.md)

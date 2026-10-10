# Changelog

All notable changes to this package are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- `telegram-bot::auth.unauthorized` translation (en, ru) — a ready-made rejection text for
  `telegram-bot.unauthorized.message`. The default is still `null` (silent).
- `telegram-bot::dialog.cancel_description` translation (en, ru).
- `webhook_url` config key (env `TELEGRAM_WEBHOOK_URL`): the base address `telegram:set-webhook`
  registers instead of `APP_URL`, e.g. a tunnel during development. Defaults to `APP_URL`.

### Changed

- **Breaking:** the webhook route moved from `/telegram/webhook/{botId}` to
  `/api/telegram/webhook/{botId}` and now runs through the `api` middleware group. The route name
  changed from `telegram.webhook` to `api.telegram.webhook`. Re-register webhooks with Telegram
  (`setWebhook`) after upgrading.
- `HelpCommand` and `CancelCommand` implement `HasDescription`, so `/help` and `/cancel` now appear in
  the `/help` list, described in the app's locale. `help.command_description` was shipped but unused.

### Fixed

- A command sent with arguments (e.g. a deep link `/start ref123`) now routes to its handler:
  `UpdateContext::command()` returned the name with a trailing space (`"start "`), so the command
  never matched. It also no longer misses the `bot_command` entity when it isn't the message's
  first entity. Arguments are still not parsed — the handler reads them from the message text.
- `Upload` files nested inside other parameters (e.g. `InputMediaPhoto` in `sendMediaGroup()`) are
  now sent. Previously they stayed inside the nested array and never reached the multipart body;
  now every nested file is replaced with its `attach://` reference and sent at the top level.
- A webhook request for an unknown bot now gets `404 Not Found` instead of a `500` error.
- Bots in the config with an empty `token` (e.g. an unset env variable) are now skipped.

## [0.5.1] - 2026-10-09

### Fixed

- Installing the package into an app with `nunomaduro/collision` no longer fails `package:discover`
  (and every other artisan command) with `Call to undefined method ...ExceptionHandler::dontReport()`.
  The `dontReport(ThrottleExceededException::class)` default is now applied to the concrete
  `Illuminate\Foundation\Exceptions\Handler` as it resolves, so it also survives Collision's console
  wrapper around the exception handler.
- `$exceptions->stopIgnoring(ThrottleExceededException::class)` in `bootstrap/app.php` now actually
  re-enables reporting — previously the package re-applied `dontReport()` after the app's
  `withExceptions()` callback had run, silently undoing it.

## [0.5.0] - 2026-09-14

### Added

- Six new events in `Appto\TelegramBot\Events\`, dispatched via Laravel's standard `event()`:
  `TelegramApiCallRequested`/`TelegramApiCallMade` (before/after every Bot API call, from
  `BaseClient::call()`), `UpdateReceived` (at the top of `Bot::dispatch()`, before middleware and
  routing), and `DialogStarted`/`DialogCompleted`/`DialogCancelled` (from `DialogManager`). Pure
  extension points — the package only dispatches them, application code decides what to do via
  `Event::listen()`; `Dialog::onComplete()`/`onCancel()` are unaffected and still run first. See
  [docs: 19. Events](docs/en/19-events.md).
- `TelegramApiCallMade` now also carries `bot: BotIdentity` (previously just `method`/`response`).

### Changed

- `Appto\TelegramBot\Client\FileInput` renamed to `Upload`, with named constructors renamed to
  match: `fromContent()` → `content()`, `fromResource()` → `resource()`, `fromFile()` → `file()`,
  `fromGdImage()` → `image()`. `file()` now reads through a Laravel `Storage` disk instead of raw
  filesystem calls — its argument is a path relative to a disk (`Storage::disk()`), not an
  arbitrary OS path, and it takes an optional `disk: ` parameter. See
  [docs: 9. Replying to users](docs/en/09-replies.md).

### Fixed

- `composer.json` required `appto-team/telegram-bot-cast-laravel: ^3.0`, but the client traits
  rely on APIs (`EphemeralMessageParameters` across most `send*()` methods) only present since
  `3.2.10.3`. Under `--prefer-lowest`, Composer could resolve an older 3.x release whose
  `AvailableMethods` interface didn't match — a PHP fatal error at class-load time. Tightened to
  `^3.2.10.3`.

## [0.4.0] - 2026-09-13

### Added

- `ThrottleMiddleware`/`ChatThrottleMiddleware` (`Update/`) — built-in per-user and per-chat rate
  limiting for incoming updates, on top of `Illuminate\Support\Facades\RateLimiter`. Configurable via
  the standard `Middleware:maxAttempts,decaySeconds` pipe syntax (defaults: 20/60).
- `ThrottleExceededException` (abstract) plus `UserThrottleExceededException`/
  `ChatThrottleExceededException` — thrown by the throttle middlewares instead of replying
  themselves. `Bot::dispatch()` catches them and calls Laravel's `report()`; the package registers
  `dontReport(ThrottleExceededException::class)` so the default behavior is silent. Apps opt into
  custom handling (reply, alert, metrics) the normal Laravel way, via `bootstrap/app.php`'s
  `stopIgnoring()` + `reportable()`.

### Fixed

- `UpdateContext::userId()`/`chatId()`/`isPrivate()` no longer emit "Undefined property" PHP
  warnings on update types that don't declare a `from`/`chat`/`message` property at all (e.g.
  `poll`, `chat_boost`) — not just when those are `null`.

## [0.3.0] - 2026-09-07

### Added

- Configurable webhook route key for `DatabaseBotRepository` — point the webhook URL at a
  custom `telegram_bots` column (e.g. a `uuid`) instead of the predictable `name`. CLI commands
  still take the bot's name; default behavior is unchanged.

### Changed

- `TelegramBotModel::boot()` no longer auto-generates `webhook_secret`. Call
  `Support\SecretGenerator::generate()` explicitly when creating a bot programmatically (e.g. a
  SaaS onboarding flow). `telegram:webhook-secret` uses the same generator and drops the
  now-unused `--length` option.

## [0.2.0] - 2026-09-07

### Added

- `telegram:webhook-secret` command.
- Laravel 12 support, in addition to 13.

### Changed

- `telegram_bots.name` is now unique; the webhook route resolves `BotIdentity` once in the
  middleware and reuses it in the controller.

### Fixed

- `UpdateContext::isPrivate()`/`update()` no longer crash on payloads without a `chat` property
  or a nullable `Update`.
- `MigrateBotsToDatabaseCommand --force` no longer wipes `webhook_secret` when the config entry
  omits it.
- `PollCommand`: fixed a dead `instanceof` check; a single failing update (`\Throwable`) no
  longer kills the whole polling loop.
- `BaseClient::retry()` now only retries connection failures, not already-sent writes.
- `DisplayUpdateInConsole`: guard `callback_query.message` for inline-mode callbacks.
- `DialogState::withStep()`/`withMergedAnswers()` now clone instead of mutating `$this`.

## [0.1.1] - 2026-08-28

### Added

- `telegram:migrate-bots-to-database` command.

### Fixed

- `CallbackHandler` signature in docs (`array $params`, not a scalar argument).

## [0.1.0] - 2026-08-28

### Added

- Initial release: multi-bot Telegram framework for Laravel — command/callback/text/update
  routing, stateful dialogs, built-in authorization and `/help`, webhook and long-polling
  delivery, config- and database-backed bot repositories.

[Unreleased]: https://github.com/appto-dev/telegram-bot/compare/v0.5.1...HEAD
[0.5.1]: https://github.com/appto-dev/telegram-bot/compare/v0.5.0...v0.5.1
[0.5.0]: https://github.com/appto-dev/telegram-bot/compare/v0.4.0...v0.5.0
[0.4.0]: https://github.com/appto-dev/telegram-bot/compare/v0.3.0...v0.4.0
[0.3.0]: https://github.com/appto-dev/telegram-bot/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/appto-dev/telegram-bot/compare/v0.1.1...v0.2.0
[0.1.1]: https://github.com/appto-dev/telegram-bot/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/appto-dev/telegram-bot/releases/tag/v0.1.0

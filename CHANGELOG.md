# Changelog

All notable changes to this package are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Six new events in `Appto\TelegramBot\Events\`, dispatched via Laravel's standard `event()`:
  `TelegramApiCallRequested`/`TelegramApiCallMade` (before/after every Bot API call, from
  `BaseClient::call()`), `UpdateReceived` (at the top of `Bot::dispatch()`, before middleware and
  routing), and `DialogStarted`/`DialogCompleted`/`DialogCancelled` (from `DialogManager`). Pure
  extension points — the package only dispatches them, application code decides what to do via
  `Event::listen()`; `Dialog::onComplete()`/`onCancel()` are unaffected and still run first. See
  [docs: 19. Events](docs/en/19-events.md).
- `TelegramApiCallMade` now also carries `bot: BotIdentity` (previously just `method`/`response`).

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

[Unreleased]: https://github.com/appto-dev/telegram-bot/compare/v0.3.0...HEAD
[0.3.0]: https://github.com/appto-dev/telegram-bot/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/appto-dev/telegram-bot/compare/v0.1.1...v0.2.0
[0.1.1]: https://github.com/appto-dev/telegram-bot/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/appto-dev/telegram-bot/releases/tag/v0.1.0

# Changelog

All notable changes to this package are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.3.1] - 2026-09-07

### Added

- Configurable webhook route key for `DatabaseBotRepository` — point the webhook URL at a
  custom `telegram_bots` column (e.g. a `uuid`) instead of the predictable `name`. CLI commands
  still take the bot's name; default behavior is unchanged.

### Changed

- `TelegramBotModel::boot()` no longer auto-generates `webhook_secret`. Call
  `Support\SecretGenerator::generate()` explicitly when creating a bot programmatically (e.g. a
  SaaS onboarding flow). `telegram:webhook-secret` uses the same generator and drops the
  now-unused `--length` option.

### Fixed

- Missing `declare(strict_types=1)` in `GenerateWebhookSecret`.

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

[Unreleased]: https://github.com/appto-dev/telegram-bot/compare/v0.3.1...HEAD
[0.3.1]: https://github.com/appto-dev/telegram-bot/compare/v0.2.0...v0.3.1
[0.2.0]: https://github.com/appto-dev/telegram-bot/compare/v0.1.1...v0.2.0
[0.1.1]: https://github.com/appto-dev/telegram-bot/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/appto-dev/telegram-bot/releases/tag/v0.1.0

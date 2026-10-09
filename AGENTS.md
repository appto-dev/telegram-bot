# AGENTS.md — appto-team/telegram-bot

Multi-bot Telegram framework for Laravel: declarative routing, step-by-step dialogs, permissions,
`/help`, webhook and long-polling delivery. Library package, namespace `Appto\TelegramBot\`.

- PHP 8.3+, Laravel 12/13 (`illuminate/*`), `spatie/laravel-data` 4, `laravel/prompts`.
- Bot API types and method interfaces (`Appto\TelegramBot\Type\*`, `Appto\TelegramBot\Support\AvailableMethods` etc.)
  come from the separate package `appto-team/telegram-bot-cast-laravel` — do not edit them here.
- User docs: `docs/ru/*.md` and `docs/en/*.md` (20 chapters each, same numbering).

## Commands

```bash
composer install          # own vendor/, no composer.lock committed
vendor/bin/phpunit        # PHPUnit 11, not Pest
vendor/bin/phpunit tests/Routing/CommandRouterTest.php
vendor/bin/pint <files>   # format changed files
```

CI runs PHP 8.3–8.5 × `prefer-lowest`/`prefer-stable`. `phpunit.xml` sets `ignoreIndirectDeprecations`, so
deprecations raised inside old vendor versions don't count — deprecations from `src/` still do.

## Layout (`src/`)

- `TelegramBotServiceProvider.php` — bindings, routes, migrations, translations, artisan commands, publish tags.
- `Bot/` — `Bot` (abstract; `boot()` registers `onCommand/onCallback/onText/onUpdate`, `middleware()`, `fallback()`),
  `BotManager`, `BotIdentity`, `BotRepository` with `ConfigBotRepository` / `DatabaseBotRepository` (`telegram_bots`, encrypted token).
- `Update/` — `UpdateContext` (update + bot, `reply*()` via `InteractsWithReplies`, `command()`, `chatId()`, `userId()`),
  `UpdateType` enum, `UpdateRouter`, middleware contract, throttle middlewares, `CacheDeduplicator`.
- `Routing/` — `RouterRegistry`, `TextRouter`, `CallbackRouter` (`{id}` / `{id?}` patterns), `CommandRouter`, `HelpCommand`.
- `Dialog/` — `Dialog`, `Step`, `StepResult`/`StepAction`, `DialogManager`, state in `telegram_dialog_states`.
- `Client/` — `BaseClient::call()` (JSON or multipart, `attach://` uploads), `TelegramClient` (Bot API traits), `Upload`.
- `Webhook/` — `WebhookController` + `VerifyWebhookSecretMiddleware`, route in `routes/webhook.php`.
- `Console/` — `telegram:poll`, `telegram:set-webhook`, `telegram:delete-webhook`, `telegram:webhook-secret`,
  `telegram:routes`, `telegram:migrate-bots-db`; console output helpers in `Console/Output`.
- `Events/`, `Exceptions/`, `Contracts/` (`CommandHandler`, `CallbackHandler`, `RequiresPermission`, `HasDescription`,
  `HasUnauthorizedMessage`), `Support/` (`HandlerInvoker`, `UnauthorizedResponder`, `SecretGenerator`).

## Update flow

`Bot::dispatch()`: `UpdateReceived` event → bot middleware (Pipeline) → active dialog (`DialogManager::handle()`,
any command cancels it) → routers in order text → callback → command → update → `fallback()`.
Handlers run through `HandlerInvoker` (container-resolved, `RequiresPermission` checked first).

## Conventions

- `declare(strict_types=1)`; classes `final` / `readonly` where possible; typed signatures; PHPDoc over inline comments.
- Text a bot user sees goes through `__('telegram-bot::file.key')`, never a literal; add every key to both
  `resources/lang/en` and `resources/lang/ru` (`tests/Lang/TranslationsTest.php` checks the pair).
  Artisan output, `telegram:poll` debug output and exception messages stay English literals.
- Comments explain *why*, in English (a few older ones are Russian — leave them).
- Tests in `tests/`, mirroring `src/` folders. `Tests\TestCase` boots a bare `Illuminate\Foundation\Application`
  (no testbench) with in-memory SQLite; build payloads with `Tests\Support\UpdateFactory`, fixtures in `tests/Fixtures`.
  HTTP is faked with `Http::fake()`.
- Every user-visible change gets an entry in `CHANGELOG.md` under `[Unreleased]` (Keep a Changelog, SemVer).
- Docs change in pairs: `docs/ru/NN-*.md` and `docs/en/NN-*.md`.
- Commits: `fix(module): ...`, `feat: ...`, `refactor: ...`, `test: ...`, `docs: ...`, `chore: ...`.

## Before every commit

Update this file and the dev app's `../CLAUDE.md` / `../AGENTS.md` if structure, commands or conventions changed.

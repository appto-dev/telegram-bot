# 17. Recipes (How-to)

## 17.1 Add a new command

1. Create a class implementing `CommandHandler` (optionally `HasDescription` for `/help`).
2. Register it in the bot's `boot()`: `$this->onCommand('name', MyCommand::class)`.

More in [5. Commands](05-commands.md).

## 17.2 Add a button with a parameter

1. Build an inline button with `callback_data` shaped like `'action:name {param}'`, matching your
   pattern (see
   [8.2](08-keyboards.md#82-inline-keyboard-buttons-under-the-message)).
2. Register the pattern: `$this->onCallback('action:name {param}', MyHandler::class)`.
3. Don't forget `$context->answerCallbackQuery()` inside the handler.

More in [6. Buttons and callback queries](06-callbacks.md).

## 17.3 Build a multi-step dialog with input validation

1. Create a `Dialog` subclass with a step list in `steps()`.
2. For each step, a `Step` class with `enter()` (what to ask) and `handle()` (validate the reply,
   return `StepResult::repeat()` on invalid input or `StepResult::next($data)` on valid input).
3. On the last step — `StepResult::complete($data)`.
4. Business logic (DB writes, etc.) only in `Dialog::onComplete()`.

More in [10. Dialogs](10-dialogs.md).

## 17.4 Restrict a command to admins only

```php
final class AdminOnlyCommand implements CommandHandler, RequiresPermission, HasUnauthorizedMessage
{
    public function handle(UpdateContext $context): void { /* ... */ }

    public function authorize(UpdateContext $context): bool
    {
        return in_array($context->userId(), config('app.admin_ids'), true);
    }

    public function unauthorizedMessage(UpdateContext $context): ?string
    {
        return 'This command is for admins only.';
    }
}
```

More in [11. Permissions](11-permissions.md).

## 17.5 Send a photo/album/document

```php
$context->replyPhoto(Upload::file('promo.jpg'), caption: 'New arrival!');
$context->replyMediaGroup([
    Upload::file('1.jpg'),
    Upload::file('2.jpg'),
]);
$context->replyDocument(Upload::file('pricelist.pdf'));
```

More in [9. Replying to users](09-replies.md).

## 17.6 Add a second bot

Add a new entry to `config('telegram-bot.bots')` with its own token and bot class — no need to
touch your existing bots' code.

More in [14. Multiple bots](14-multiple-bots.md).

## 17.7 Switch from config-based to database-backed bot storage

```bash
php artisan vendor:publish --tag=telegram-bot-migrations
php artisan migrate
```

Then set `TELEGRAM_BOT_REPOSITORY=database` in `.env`.

More in [15. Bot source](15-bot-source.md).

## 17.8 Translate the bot's texts into several languages

The package uses standard Laravel translations. Write your own texts in handlers through `__()` too,
not as literals:

```php
$context->reply(__('bot.welcome', ['name' => $context->message()->from->first_name]));
```

```php
// lang/en/bot.php
return [
    'welcome' => 'Hi, :name!',
    'menu' => ['catalog' => 'Catalog'],
];
```

**The package's own texts** live in the `telegram-bot::` namespace (English and Russian):

| Key | Used for |
|-----|----------|
| `telegram-bot::help.title` | `/help` list title |
| `telegram-bot::help.empty` | `/help` reply when there is nothing to show |
| `telegram-bot::help.command_description` | `/help` description in the list |
| `telegram-bot::dialog.cancel_description` | `/cancel` description in the list |
| `telegram-bot::auth.unauthorized` | Ready-made rejection text for `unauthorized.message` (see [11.3](11-permissions.md#113-a-bot-wide-default-message)) |

To change the wording or add a language, publish the files to `lang/vendor/telegram-bot/` and edit
them there:

```bash
php artisan vendor:publish --tag=telegram-bot-lang
```

**The user's language.** By default everything replies in `APP_LOCALE`. To make the bot speak the
user's language, set the locale in a middleware from the `language_code` Telegram sends in `from`:

```php
use Appto\TelegramBot\Update\UpdateContext;
use Appto\TelegramBot\Update\UpdateMiddleware;
use Appto\TelegramBot\Update\UpdateType;
use Closure;
use Illuminate\Support\Facades\App;

final class SetUserLocale implements UpdateMiddleware
{
    private const array SUPPORTED = ['ru', 'en'];

    public function handle(UpdateContext $context, Closure $next): void
    {
        $update = $context->update();
        $type = UpdateType::detect($update);
        $language = $type ? ($update->{$type->value}->from ?? null)?->language_code : null;

        App::setLocale(in_array($language, self::SUPPORTED, true) ? $language : config('app.fallback_locale'));

        $next($context);
    }
}
```

```php
// in the bot's boot()
$this->middleware([SetUserLocale::class]);
```

Set the locale on **every** update, including back to the default: `telegram:poll` and queue
workers are long-running processes, and the previous user's language would otherwise stick to the
next one. Take the default from `app.fallback_locale`, not `app.locale`: `App::setLocale()` overwrites
`app.locale`, so after the first user it would hold their language. If users pick a language in the bot's settings, read it from your users table instead of
`language_code`.

**Reply-keyboard button labels.** `onText()` compares the text as is, and `boot()` runs once, in the
default locale. Register the button for every language:

```php
foreach (['ru', 'en'] as $locale) {
    $this->onText(__('bot.menu.catalog', locale: $locale), CatalogHandler::class);
}
```

## 17.9 Store translations in the database

If the bot's texts are edited by non-developers (e.g. from an admin panel), you can keep translations
in the database with [spatie/laravel-translation-loader](https://github.com/spatie/laravel-translation-loader) —
it replaces Laravel's translation loader, so `__()` reads strings from the `language_lines` table on
top of the files.

```bash
composer require spatie/laravel-translation-loader
php artisan vendor:publish --provider="Spatie\TranslationLoader\TranslationServiceProvider" --tag="translation-loader-migrations"
php artisan migrate
```

Replace the default translation provider in `config/app.php`:

```php
use Illuminate\Support\ServiceProvider;

'providers' => ServiceProvider::defaultProviders()->replace([
    Illuminate\Translation\TranslationServiceProvider::class => Spatie\TranslationLoader\TranslationServiceProvider::class,
])->toArray(),
```

Add a line and it works in handlers right away via `__('bot.welcome')`:

```php
use Spatie\TranslationLoader\LanguageLine;

LanguageLine::create([
    'group' => 'bot',
    'key' => 'welcome',
    'text' => ['en' => 'Hi, :name!', 'ru' => 'Привет, :name!'],
]);
```

### The package's texts (`telegram-bot::…`) from the database

spatie's default manager reads only keys **without a namespace** from the database: for
`__('telegram-bot::auth.unauthorized')` it returns the file straight away and never queries the
table. To keep the package's translations in the database too, you need your own manager, e.g.
`NamespacedTranslationLoaderManager` below.

> **Caveat.** spatie itself doesn't pass the namespace to the loaders: the built-in `Db` loader
> ignores it, and `$namespace` is optional on the `TranslationLoader` interface. The manager below
> passes the namespace as the third argument and will only work once spatie accepts the changes from
> [CriztianiX/laravel-translation-loader@feat/pass-namespace-var](https://github.com/CriztianiX/laravel-translation-loader/tree/feat/pass-namespace-var).
> Until then, change the package's texts via files: `vendor:publish --tag=telegram-bot-lang`
> (see [17.8](#178-translate-the-bots-texts-into-several-languages)).

```php
<?php

declare(strict_types=1);

namespace App\Translation;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Spatie\TranslationLoader\TranslationLoaderManager;
use Spatie\TranslationLoader\TranslationLoaders\TranslationLoader;

/**
 * Also reads namespaced keys (telegram-bot::auth.unauthorized) from the translation loaders.
 * In language_lines such a line is stored with group "telegram-bot::auth", key "unauthorized".
 */
final class NamespacedTranslationLoaderManager extends TranslationLoaderManager
{
    public function load($locale, $group, $namespace = null): array
    {
        $fileTranslations = parent::load($locale, $group, $namespace);

        if ($namespace === null || $namespace === '*') {
            return $fileTranslations;
        }

        try {
            $loaderTranslations = [];

            foreach (config('translation-loader.translation_loaders') as $class) {
                /** @var TranslationLoader $loader */
                $loader = app($class);

                $loaderTranslations = array_replace_recursive(
                    $loaderTranslations,
                    $loader->loadTranslations($locale, "{$namespace}::{$group}", $namespace),
                );
            }
        } catch (QueryException $exception) {
            $modelClass = config('translation-loader.model');

            if (! Schema::hasTable((new $modelClass)->getTable())) {
                return $fileTranslations;
            }

            throw $exception;
        }

        return array_replace_recursive($fileTranslations, $loaderTranslations);
    }
}
```

Register it in spatie's config (`php artisan vendor:publish --provider="Spatie\TranslationLoader\TranslationServiceProvider" --tag="translation-loader-config"`):

```php
// config/translation-loader.php
'translation_manager' => App\Translation\NamespacedTranslationLoaderManager::class,
```

A line for the package is stored with a `namespace::group` group:

```php
LanguageLine::create([
    'group' => 'telegram-bot::auth',
    'key' => 'unauthorized',
    'text' => ['en' => 'Admins only.', 'ru' => 'Эта команда только для администраторов.'],
]);
```

Database lines override the files; keys without a database row still come from the package files.
If the `language_lines` table doesn't exist yet (e.g. before the first migration), the manager
silently falls back to the files — just like spatie's default manager.

## Next

→ [18. Known limitations](18-limitations.md)

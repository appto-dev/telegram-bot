# 17. Рецепты (How-to)

## 17.1 Добавить новую команду

1. Создайте класс, реализующий `CommandHandler` (по желанию — `HasDescription` для `/help`).
2. Зарегистрируйте её в `boot()` бота: `$this->onCommand('имя', MyCommand::class)`.

Подробнее — [5. Команды](05-commands.md).

## 17.2 Добавить кнопку с параметром

1. Создайте инлайн-кнопку с `callback_data` вида `'action:name {param}'` формата, ожидаемого
   вашим паттерном (см. [8.2](08-keyboards.md#82-inline-клавиатура-кнопки-под-сообщением)).
2. Зарегистрируйте паттерн: `$this->onCallback('action:name {param}', MyHandler::class)`.
3. Не забудьте `$context->answerCallbackQuery()` внутри хендлера.

Подробнее — [6. Кнопки и callback-запросы](06-callbacks.md).

## 17.3 Собрать диалог из нескольких шагов с проверкой ввода

1. Создайте класс-наследник `Dialog` со списком шагов в `steps()`.
2. На каждый шаг — класс `Step` с `enter()` (что спросить) и `handle()` (проверить ответ, вернуть
   `StepResult::repeat()` при невалидном вводе или `StepResult::next($data)` при валидном).
3. На последнем шаге — `StepResult::complete($data)`.
4. Бизнес-логику (запись в БД и т. п.) — только в `Dialog::onComplete()`.

Подробнее — [10. Диалоги](10-dialogs.md).

## 17.4 Закрыть команду только для администраторов

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
        return 'Команда доступна только администраторам.';
    }
}
```

Подробнее — [11. Права доступа](11-permissions.md).

## 17.5 Отправить фото/альбом/документ

```php
$context->replyPhoto(Upload::file('promo.jpg'), caption: 'Новинка!');
$context->replyMediaGroup([
    Upload::file('1.jpg'),
    Upload::file('2.jpg'),
]);
$context->replyDocument(Upload::file('prайс.pdf'));
```

Подробнее — [9. Ответы пользователю](09-replies.md).

## 17.6 Подключить второго бота

Добавьте новую запись в `config('telegram-bot.bots')` со своим токеном и классом бота — код уже
существующих ботов трогать не нужно.

Подробнее — [14. Несколько ботов](14-multiple-bots.md).

## 17.7 Перейти с конфига на базу данных для списка ботов

```bash
php artisan vendor:publish --tag=telegram-bot-migrations
php artisan migrate
```

Затем — `TELEGRAM_BOT_REPOSITORY=database` в `.env`.

Подробнее — [15. Источник списка ботов](15-bot-source.md).

## 17.8 Перевести тексты бота на несколько языков

Пакет использует стандартные переводы Laravel. Свои тексты в хендлерах тоже пишите через `__()`, а
не строкой в коде:

```php
$context->reply(__('bot.welcome', ['name' => $context->message()->from->first_name]));
```

```php
// lang/ru/bot.php
return [
    'welcome' => 'Привет, :name!',
    'menu' => ['catalog' => 'Каталог'],
];
```

**Тексты самого пакета** лежат в namespace `telegram-bot::` (русский и английский):

| Ключ | Где используется |
|------|------------------|
| `telegram-bot::help.title` | Заголовок списка `/help` |
| `telegram-bot::help.empty` | Ответ `/help`, когда показывать нечего |
| `telegram-bot::help.command_description` | Описание `/help` в списке |
| `telegram-bot::dialog.cancel_description` | Описание `/cancel` в списке |
| `telegram-bot::auth.unauthorized` | Готовый текст отказа для `unauthorized.message` (см. [11.3](11-permissions.md#113-общее-сообщение-на-уровне-всего-бота)) |

Чтобы поменять формулировки или добавить язык, опубликуйте файлы в `lang/vendor/telegram-bot/` и
правьте их там:

```bash
php artisan vendor:publish --tag=telegram-bot-lang
```

**Язык пользователя.** По умолчанию всё отвечает на `APP_LOCALE`. Чтобы бот говорил на языке
пользователя, выставляйте локаль в middleware по `language_code`, который Telegram присылает в `from`:

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
// в boot() бота
$this->middleware([SetUserLocale::class]);
```

Локаль выставляется на **каждый** апдейт, в том числе обратно на язык по умолчанию: `telegram:poll`
и воркеры очередей — долгоживущие процессы, и язык предыдущего пользователя иначе «прилипнет» к
следующему. Язык по умолчанию берите из `app.fallback_locale`, а не из `app.locale`:
`App::setLocale()` перезаписывает `app.locale`, и после первого же пользователя там окажется его язык. Если язык выбирают в настройках бота, берите его из своей таблицы пользователей вместо
`language_code`.

**Тексты кнопок reply-клавиатуры.** `onText()` сравнивает текст как есть, а `boot()` выполняется
один раз, на языке по умолчанию. Регистрируйте кнопку для каждого языка:

```php
foreach (['ru', 'en'] as $locale) {
    $this->onText(__('bot.menu.catalog', locale: $locale), CatalogHandler::class);
}
```

## 17.9 Хранить переводы в базе данных

Если тексты бота должны править не разработчики (например, из админки), переводы можно хранить в
БД через [spatie/laravel-translation-loader](https://github.com/spatie/laravel-translation-loader) —
он подменяет загрузчик переводов Laravel, и `__()` начинает брать строки из таблицы `language_lines`
поверх файлов.

```bash
composer require spatie/laravel-translation-loader
php artisan vendor:publish --provider="Spatie\TranslationLoader\TranslationServiceProvider" --tag="translation-loader-migrations"
php artisan migrate
```

Замените стандартный провайдер переводов в `config/app.php`:

```php
use Illuminate\Support\ServiceProvider;

'providers' => ServiceProvider::defaultProviders()->replace([
    Illuminate\Translation\TranslationServiceProvider::class => Spatie\TranslationLoader\TranslationServiceProvider::class,
])->toArray(),
```

Добавьте строку — и она сразу работает в хендлерах через `__('bot.welcome')`:

```php
use Spatie\TranslationLoader\LanguageLine;

LanguageLine::create([
    'group' => 'bot',
    'key' => 'welcome',
    'text' => ['ru' => 'Привет, :name!', 'en' => 'Hi, :name!'],
]);
```

### Тексты пакета (`telegram-bot::…`) из базы

Стандартный менеджер spatie берёт из БД только ключи **без namespace**: для
`__('telegram-bot::auth.unauthorized')` он сразу возвращает файл и в базу не заглядывает. Чтобы
переводы пакета тоже можно было хранить в БД, нужен собственный менеджер, например
`NamespacedTranslationLoaderManager` ниже.

> **Нюанс.** Сам spatie не передаёт namespace в загрузчики: встроенный `Db` его игнорирует, а у
> интерфейса `TranslationLoader` параметр `$namespace` необязательный. Менеджер ниже передаёт
> namespace третьим аргументом и заработает, только если spatie примут правки из
> [CriztianiX/laravel-translation-loader@feat/pass-namespace-var](https://github.com/CriztianiX/laravel-translation-loader/tree/feat/pass-namespace-var).
> До этого тексты пакета меняйте через файлы: `vendor:publish --tag=telegram-bot-lang`
> (см. [17.8](#178-перевести-тексты-бота-на-несколько-языков)).

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

Подключите его в конфиге spatie (`php artisan vendor:publish --provider="Spatie\TranslationLoader\TranslationServiceProvider" --tag="translation-loader-config"`):

```php
// config/translation-loader.php
'translation_manager' => App\Translation\NamespacedTranslationLoaderManager::class,
```

Строка для пакета хранится с группой вида `namespace::группа`:

```php
LanguageLine::create([
    'group' => 'telegram-bot::auth',
    'key' => 'unauthorized',
    'text' => ['ru' => 'Эта команда только для администраторов.', 'en' => 'Admins only.'],
]);
```

Строки из БД перекрывают файлы, ключи без записи в БД по-прежнему берутся из файлов пакета. Если
таблицы `language_lines` ещё нет (например, до первой миграции), менеджер молча отдаёт файлы — как и
стандартный менеджер spatie.

## Дальше

→ [18. Известные ограничения](18-limitations.md)

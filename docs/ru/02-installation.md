# 2. Установка

## 2.1 Composer

```bash
composer require appto-team/telegram-bot
php artisan vendor:publish --tag=telegram-bot-config
php artisan migrate
```

Отдельно можно опубликовать только нужное:

```bash
php artisan vendor:publish --tag=telegram-bot-config       # только конфиг
php artisan vendor:publish --tag=telegram-bot-migrations   # только миграции
php artisan vendor:publish --tag=telegram-bot-lang         # только переводы
```

Миграции создают таблицу состояний диалогов (`telegram_dialog_states`). Таблица `telegram_bots`
(для хранения ботов в БД, см. [15. Источник списка ботов](15-bot-source.md)) публикуется отдельно
и по умолчанию не нужна, если вы храните ботов в конфиге.

## 2.2 Переменные окружения

Минимум для одного бота:

```env
TELEGRAM_BOT_TOKEN=123456:AA...
TELEGRAM_BOT_WEBHOOK_SECRET=любая-случайная-строка
```

> ⚠️ **`TELEGRAM_BOT_TOKEN` и `TELEGRAM_BOT_WEBHOOK_SECRET` — конфиденциальные данные.** Не
> коммитьте их в git, не публикуйте в issue/чатах/скриншотах и не передавайте третьим лицам.
> Токен даёт полный доступ к боту (отправка сообщений от его имени, чтение апдейтов, смена
> вебхука), а секрет вебхука защищает эндпоинт от поддельных запросов — утечка любого из них
> означает, что токен бота нужно немедленно перевыпускать (через @BotFather, секрет —
> командой из §2.2 выше) и обновлять на всех окружениях.

Полезные, но не обязательные:

```env
TELEGRAM_BOT_REPOSITORY=config        # или database, см. §15
TELEGRAM_API_BASE_URI=https://api.telegram.org   # менять только для локального Bot API Server
TELEGRAM_BOT_UNAUTHORIZED_MESSAGE=    # см. §11 «Права доступа»
```

Случайную строку для `TELEGRAM_BOT_WEBHOOK_SECRET` не обязательно придумывать вручную — пакет
умеет генерировать её сам:

```bash
php artisan telegram:webhook-secret
```

Команда выводит готовую строку в терминал (48 hex-символов, `bin2hex(random_bytes(24))`) — её
нужно скопировать в `.env`. Сама команда апдейты не отправляет и файл не правит, только генерирует
значение.

Тот же алгоритм доступен и из кода — `Appto\TelegramBot\Support\SecretGenerator::generate()`.
Пакет больше не генерирует `webhook_secret` автоматически при создании записи в `telegram_bots`
(при `repository = database`) — если он нужен сразу при программном создании бота (например, в
своём SaaS-флоу подключения ботов), вызовите генератор сами:

```php
use Appto\TelegramBot\Bot\TelegramBotModel;
use Appto\TelegramBot\Support\SecretGenerator;

TelegramBotModel::create([
    'name' => 'shop',
    'token' => $token,
    'handler' => \App\MyBot\MyBot::class,
    'webhook_secret' => SecretGenerator::generate(),
]);
```

`generate(int $bytes = 24)` принимает необязательный аргумент — длину секрета в байтах (итоговая
строка в hex вдвое длиннее).

## 2.3 Минимальный конфиг

`config/telegram-bot.php` после публикации уже содержит рабочий каркас — нужно прописать бота:

```php
'bots' => [
    'default' => [
        'token' => env('TELEGRAM_BOT_TOKEN'),
        'webhook_secret' => env('TELEGRAM_BOT_WEBHOOK_SECRET'),
        'handler' => \App\MyBot\MyBot::class,
    ],
],
```

Ключ массива (`default`) — произвольный алиас бота: он используется в маршруте вебхука и во всех
artisan-командах (`telegram:poll default`, `telegram:routes default`, …).

## 2.4 Первая проверка

Без реального бота ещё не обойтись — понадобится токен от [@BotFather](https://t.me/BotFather).
Как только он есть и прописан в `.env`, самый быстрый способ проверить, что всё подключилось:

```bash
php artisan telegram:poll default
```

Команда начнёт получать апдейты через long polling — этого достаточно для локальной разработки,
публичный HTTPS-домен для вебхука на этом этапе не нужен. Подробнее — в
[13. Webhook и long polling](13-delivery.md).

## Дальше

→ [3. Как устроена разработка бота](03-development-philosophy.md)

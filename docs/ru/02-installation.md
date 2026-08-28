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

Полезные, но не обязательные:

```env
TELEGRAM_BOT_REPOSITORY=config        # или database, см. §15
TELEGRAM_API_BASE_URI=https://api.telegram.org   # менять только для локального Bot API Server
TELEGRAM_BOT_UNAUTHORIZED_MESSAGE=    # см. §11 «Права доступа»
```

## 2.3 Минимальный конфиг

`config/telegram-bot.php` после публикации уже содержит рабочий каркас — нужно прописать бота:

```php
'bots' => [
    'shop' => [
        'token' => env('TELEGRAM_BOT_TOKEN'),
        'webhook_secret' => env('TELEGRAM_BOT_WEBHOOK_SECRET'),
        'bot' => \App\ShopBot\ShopBot::class,
    ],
],
```

Ключ массива (`shop`) — произвольный алиас бота: он используется в маршруте вебхука и во всех
artisan-командах (`telegram:poll shop`, `telegram:routes shop`, …).

## 2.4 Первая проверка

Без реального бота ещё не обойтись — понадобится токен от [@BotFather](https://t.me/BotFather).
Как только он есть и прописан в `.env`, самый быстрый способ проверить, что всё подключилось:

```bash
php artisan telegram:poll shop
```

Команда начнёт получать апдейты через long polling — этого достаточно для локальной разработки,
публичный HTTPS-домен для вебхука на этом этапе не нужен. Подробнее — в
[13. Webhook и long polling](13-delivery.md).

## Дальше

→ [3. Как устроена разработка бота](03-development-philosophy.md)

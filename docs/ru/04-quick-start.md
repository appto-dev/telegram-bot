# 4. Быстрый старт

Соберём минимального бота с одной командой `/start`.

## 4.1 Класс бота

```php
// app/GreetBot/GreetBot.php
namespace App\GreetBot;

use App\GreetBot\Commands\StartCommand;
use Appto\TelegramBot\Bot\Bot;

class GreetBot extends Bot
{
    protected function boot(): void
    {
        $this->onCommand('start', StartCommand::class);
    }
}
```

## 4.2 Хендлер команды

```php
// app/GreetBot/Commands/StartCommand.php
namespace App\GreetBot\Commands;

use Appto\TelegramBot\Contracts\CommandHandler;
use Appto\TelegramBot\Contracts\HasDescription;
use Appto\TelegramBot\Update\UpdateContext;

class StartCommand implements CommandHandler, HasDescription
{
    public function handle(UpdateContext $context): void
    {
        $context->reply('Привет! Я на связи 👋');
    }

    public static function description(): string
    {
        return 'Начать работу с ботом';
    }
}
```

`HasDescription` не обязателен — он нужен только для того, чтобы команда появилась в `/help`
(см. [5. Команды](05-commands.md)).

## 4.3 Подключаем в конфиг

```php
// config/telegram-bot.php
'bots' => [
    'greet' => [
        'token' => env('TELEGRAM_BOT_TOKEN'),
        'webhook_secret' => env('TELEGRAM_BOT_WEBHOOK_SECRET'),
        'bot' => \App\GreetBot\GreetBot::class,
    ],
],
```

## 4.4 Запускаем и проверяем

```bash
php artisan telegram:poll greet
```

Отправьте боту `/start` в Telegram — должен прийти ответ «Привет! Я на связи 👋». Если ответа нет —
см. чек-лист в [16. Отладка и диагностика](16-debugging.md).

Когда пора выкладывать в продакшен — вместо long polling настраивается webhook, см.
[13. Webhook и long polling](13-delivery.md).

## Дальше

→ [5. Команды](05-commands.md)

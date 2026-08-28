# 5. Команды

## 5.1 Регистрация

```php
$this->onCommand('start', StartCommand::class);
```

Первый аргумент — имя команды без слэша. Фреймворк сам обрежет `/` и суффикс вида `@your_bot`,
который Telegram-клиент иногда подставляет в группах.

Хендлером может быть класс, реализующий `CommandHandler`, либо обычный callable:

```php
$this->onCommand('ping', fn (UpdateContext $context) => $context->reply('pong'));
```

Класс предпочтительнее для всего, что сложнее одной строки — его проще тестировать и переиспользовать.

## 5.2 Класс-обработчик

```php
class CabinetCommand implements CommandHandler, HasDescription
{
    public function handle(UpdateContext $context): void
    {
        $context->reply('Ваш кабинет');
    }

    public static function description(): string
    {
        return 'Открыть личный кабинет';
    }
}
```

`handle()` — единственный обязательный метод. Всё остальное — контекст апдейта (`UpdateContext`),
через который можно достать текст сообщения, отправителя, ответить и так далее (см.
[9. Ответы пользователю](09-replies.md)).

## 5.3 Команда в `/help`

Встроенная команда `/help` собирает список из всех зарегистрированных команд, которые реализуют
`HasDescription`, и показывает пользователю только те, на которые у него есть права (см. §5.4).

Подключается как обычная команда:

```php
use Appto\TelegramBot\Routing\HelpCommand;

$this->onCommand('help', HelpCommand::class);
```

Если команда не реализует `HasDescription` — она просто не попадёт в список `/help`, но продолжит
работать как обычно.

## 5.4 Ограничение доступа

Если команда должна быть доступна не всем — реализуйте `RequiresPermission`:

```php
final class AdminPanel implements CommandHandler, RequiresPermission
{
    public function handle(UpdateContext $context): void { /* ... */ }

    public function authorize(UpdateContext $context): bool
    {
        return $context->userId() === 123456;
    }
}
```

Фреймворк сам вызовет `authorize()` перед `handle()`. При `false` пользователь получит отказ (см.
[11. Права доступа](11-permissions.md)) — вам не нужно проверять права внутри `handle()`.

## Дальше

→ [6. Кнопки и callback-запросы](06-callbacks.md)

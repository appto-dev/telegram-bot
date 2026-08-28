# 11. Права доступа

## 11.1 Ограничение конкретного хендлера

Любая команда, callback-хендлер или диалог может ограничить доступ, реализовав интерфейс
`RequiresPermission`:

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

Фреймворк сам вызывает `authorize()` перед `handle()` — оборачивать проверку прав внутрь
бизнес-логики не нужно. Если `authorize()` вернул `false`, `handle()` не выполнится вовсе.

Для `Dialog` работает точно так же — если диалог реализует `RequiresPermission`, проверка
выполняется один раз при входе в диалог (на команду/кнопку, с которой он запускается).

## 11.2 Сообщение при отказе

По умолчанию при отказе пользователь просто не получает никакой реакции. Чтобы показать сообщение,
хендлер дополнительно реализует `HasUnauthorizedMessage`:

```php
public function unauthorizedMessage(UpdateContext $context): ?string
{
    return 'Этот раздел доступен только администраторам.';
}
```

Для callback-запросов сообщение показывается как алерт (через `answerCallbackQuery`), для всех
остальных типов апдейтов — как обычный текстовый ответ.

## 11.3 Общее сообщение на уровне всего бота

Если у большинства закрытых команд должно быть одно и то же сообщение об отказе, проще не
дублировать `HasUnauthorizedMessage` в каждом классе, а задать текст по умолчанию в конфиге:

```php
// config/telegram-bot.php
'unauthorized' => [
    'message' => env('TELEGRAM_BOT_UNAUTHORIZED_MESSAGE'),
    'show_alert' => false,
],
```

Он используется, если конкретный хендлер не переопределил своё сообщение через
`HasUnauthorizedMessage`.

## 11.4 Влияние на `/help`

Встроенная команда `/help` (см. [5.3](05-commands.md#53-команда-в-help)) автоматически показывает
пользователю только те команды, на которые у него есть права по `RequiresPermission` — закрытые от
него команды в списке просто не появятся, без дополнительной настройки.

## Дальше

→ [12. Middleware](12-middleware.md)

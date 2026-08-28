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
$context->replyPhoto(FileInput::fromFile(storage_path('app/promo.jpg')), caption: 'Новинка!');
$context->replyMediaGroup([
    FileInput::fromFile(storage_path('app/1.jpg')),
    FileInput::fromFile(storage_path('app/2.jpg')),
]);
$context->replyDocument(FileInput::fromFile(storage_path('app/prайс.pdf')));
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

## Дальше

→ [18. Известные ограничения](18-limitations.md)

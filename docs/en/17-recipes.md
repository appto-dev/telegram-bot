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
$context->replyPhoto(FileInput::fromFile(storage_path('app/promo.jpg')), caption: 'New arrival!');
$context->replyMediaGroup([
    FileInput::fromFile(storage_path('app/1.jpg')),
    FileInput::fromFile(storage_path('app/2.jpg')),
]);
$context->replyDocument(FileInput::fromFile(storage_path('app/pricelist.pdf')));
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

## Next

→ [18. Known limitations](18-limitations.md)

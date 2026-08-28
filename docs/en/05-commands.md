# 5. Commands

## 5.1 Registration

```php
$this->onCommand('start', StartCommand::class);
```

The first argument is the command name without the slash. The framework strips the leading `/`
and any `@your_bot` suffix that Telegram clients sometimes add in group chats.

The handler can be a class implementing `CommandHandler`, or a plain callable:

```php
$this->onCommand('ping', fn (UpdateContext $context) => $context->reply('pong'));
```

A class is preferable for anything more complex than a one-liner — easier to test and reuse.

## 5.2 Handler class

```php
class CabinetCommand implements CommandHandler, HasDescription
{
    public function handle(UpdateContext $context): void
    {
        $context->reply('Your cabinet');
    }

    public static function description(): string
    {
        return 'Open your personal cabinet';
    }
}
```

`handle()` is the only required method. Everything else comes from the update context
(`UpdateContext`), which gives you the message text, the sender, reply methods, and so on (see
[9. Replying to users](09-replies.md)).

## 5.3 Showing up in `/help`

The built-in `/help` command lists every registered command that implements `HasDescription`, and
only shows the user the ones they're authorized for (see §5.4).

It's registered like any other command:

```php
use Appto\TelegramBot\Routing\HelpCommand;

$this->onCommand('help', HelpCommand::class);
```

A command that doesn't implement `HasDescription` simply won't appear in `/help` — it keeps
working normally otherwise.

## 5.4 Restricting access

If a command shouldn't be available to everyone, implement `RequiresPermission`:

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

The framework calls `authorize()` before `handle()` on its own. On `false` the user gets rejected
(see [11. Permissions](11-permissions.md)) — you don't need to check permissions inside `handle()`.

## Next

→ [6. Buttons and callback queries](06-callbacks.md)

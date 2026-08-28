# 11. Permissions

## 11.1 Restricting a specific handler

Any command, callback handler, or dialog can opt into access control by implementing
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

The framework calls `authorize()` before `handle()` on its own — there's no need to wrap permission
checks inside your business logic. If `authorize()` returns `false`, `handle()` never runs.

`Dialog` works the same way — if a dialog implements `RequiresPermission`, the check runs once,
when the dialog is entered (on the command/button it's registered against).

## 11.2 The rejection message

By default, a rejected user gets no response at all. To show a message, the handler additionally
implements `HasUnauthorizedMessage`:

```php
public function unauthorizedMessage(UpdateContext $context): ?string
{
    return 'This section is for admins only.';
}
```

For callback queries, the message is shown as an alert (via `answerCallbackQuery`); for everything
else, as a regular text reply.

## 11.3 A bot-wide default message

If most of your restricted commands should share the same rejection message, you don't have to
repeat `HasUnauthorizedMessage` in every class — set a default in the config instead:

```php
// config/telegram-bot.php
'unauthorized' => [
    'message' => env('TELEGRAM_BOT_UNAUTHORIZED_MESSAGE'),
    'show_alert' => false,
],
```

It's used whenever a handler doesn't override its own message via `HasUnauthorizedMessage`.

## 11.4 Effect on `/help`

The built-in `/help` command (see
[5.3](05-commands.md#53-showing-up-in-help)) automatically shows the user only the commands they're
authorized for per `RequiresPermission` — commands they can't access simply don't appear in the
list, no extra configuration needed.

## Next

→ [12. Middleware](12-middleware.md)

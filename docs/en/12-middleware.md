# 12. Middleware

## 12.1 What it's for

`RequiresPermission` (see [11. Permissions](11-permissions.md)) answers "can this specific user run
this specific command". Middleware is for checks and side effects that should run for *every*
update a bot receives, before the framework even starts looking for a matching handler: CAPTCHA,
rate-limiting, logging, blocking banned users, detecting the user's language, and so on.

## 12.2 Writing your own

```php
namespace App\MyBot\Middleware;

use Appto\TelegramBot\Update\UpdateContext;
use Appto\TelegramBot\Update\UpdateMiddleware;
use Closure;

class CaptchaMiddleware implements UpdateMiddleware
{
    public function handle(UpdateContext $context, Closure $next): void
    {
        if ($this->userNeedsCaptcha($context)) {
            $context->reply("Please verify you're not a bot: …");

            return; // don't call $next() — update processing stops here
        }

        $next($context);
    }
}
```

Middleware runs on `Illuminate\Pipeline`, so the logic is the same as Laravel HTTP middleware: if
you don't call `$next($context)`, the chain (and any further routing of the update) stops.

## 12.3 Registration and order

```php
protected function boot(): void
{
    $this->middleware([
        BanCheckMiddleware::class,
        CaptchaMiddleware::class,
    ]);
}
```

Middleware runs in the order listed, before the update reaches an active dialog or gets routed to
commands/buttons/text — meaning middleware sees literally every incoming update for this bot, no
exceptions.

## Next

→ [13. Webhook and long polling](13-delivery.md)

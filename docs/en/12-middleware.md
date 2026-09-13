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

## 12.4 Built-in limiters: ThrottleMiddleware and ChatThrottleMiddleware

The package ships two ready-made middlewares that protect a bot from flooding, so you don't have to
write `RateLimiter` logic yourself:

```php
use Appto\TelegramBot\Update\ChatThrottleMiddleware;
use Appto\TelegramBot\Update\ThrottleMiddleware;

protected function boot(): void
{
    $this->middleware([
        ThrottleMiddleware::class.':20,60',       // at most 20 updates / 60s from one user
        ChatThrottleMiddleware::class.':100,60',  // and at most 100 updates / 60s for the whole chat
    ]);
}
```

- `ThrottleMiddleware` counts the limit per user (`userId()`, falling back to `chatId()` for updates
  with no sender at all — e.g. channel posts).
- `ChatThrottleMiddleware` counts the limit for the whole chat, regardless of who's actually
  writing — useful in groups, where each member individually stays under their own limit but the
  chat as a whole still gets flooded.
- The `maxAttempts,decaySeconds` parameters use `Illuminate\Pipeline`'s normal `Middleware:params`
  syntax; without them the defaults are 20 attempts / 60 seconds.

Once the limit is exceeded, the middleware **doesn't reply to the user itself** — it throws
`UserThrottleExceededException`/`ChatThrottleExceededException` (both subclasses of the abstract
`ThrottleExceededException`, carrying `$context` and `$retryAfter` — seconds left until the limit
resets). `Bot::dispatch()` catches it and hands it to Laravel's own `report()`. The package registers
`dontReport(ThrottleExceededException::class)` on its own, so by default it's completely silent — no
log entry, no reply. To react to it (reply, send an alert, record a metric) — the normal Laravel way,
in `bootstrap/app.php`, no package-specific API involved:

```php
use Appto\TelegramBot\Exceptions\ThrottleExceededException;
use Appto\TelegramBot\Exceptions\UserThrottleExceededException;
use Illuminate\Foundation\Configuration\Exceptions;

->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->stopIgnoring(ThrottleExceededException::class); // lift the package's default silence
    $exceptions->reportable(function (UserThrottleExceededException $e): void {
        $e->context->reply("You're doing that too often, please slow down.");
    });
})
```

`$e->context` is the same `UpdateContext` the middleware received: `->bot->id`, `->chatId()`,
`->userId()`, `->reply()` and the rest are all available, so you can see exactly which bot/chat/user
tripped the limit and reply right there.

## Next

→ [13. Webhook and long polling](13-delivery.md)

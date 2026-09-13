# 12. Middleware

## 12.1 Зачем нужен

`RequiresPermission` (см. [11. Права доступа](11-permissions.md)) решает вопрос «может ли этот
конкретный пользователь выполнить именно эту команду». Middleware — для проверок и действий,
которые должны выполняться для *всех* апдейтов бота ещё до того, как фреймворк начнёт искать,
какой хендлер вызвать: капча, троттлинг частых сообщений, логирование, блокировка забаненных
пользователей, определение языка и так далее.

## 12.2 Как написать свой

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
            $context->reply('Подтвердите, что вы не бот: …');

            return; // не вызываем $next() — обработка апдейта на этом останавливается
        }

        $next($context);
    }
}
```

Middleware строится на `Illuminate\Pipeline`, поэтому логика такая же, как у HTTP middleware в
Laravel: не вызвали `$next($context)` — цепочка (и дальнейшая маршрутизация апдейта) прерывается.

## 12.3 Подключение и порядок

```php
protected function boot(): void
{
    $this->middleware([
        BanCheckMiddleware::class,
        CaptchaMiddleware::class,
    ]);
}
```

Middleware выполняются в указанном порядке, до того как апдейт дойдёт до активного диалога или до
роутинга по командам/кнопкам/тексту — то есть middleware видит вообще каждый входящий апдейт этого
бота, без исключений.

## 12.4 Встроенные лимитеры: ThrottleMiddleware и ChatThrottleMiddleware

Пакет поставляет два готовых middleware для защиты бота от флуда — не нужно писать логику на
`RateLimiter` самостоятельно:

```php
use Appto\TelegramBot\Update\ChatThrottleMiddleware;
use Appto\TelegramBot\Update\ThrottleMiddleware;

protected function boot(): void
{
    $this->middleware([
        ThrottleMiddleware::class.':20,60',       // не больше 20 апдейтов за 60 сек от одного юзера
        ChatThrottleMiddleware::class.':100,60',  // и не больше 100 апдейтов за 60 сек на весь чат
    ]);
}
```

- `ThrottleMiddleware` считает лимит на пользователя (`userId()`, с фоллбэком на `chatId()` для
  апдейтов без отправителя — например постов в канале).
- `ChatThrottleMiddleware` считает лимит на весь чат целиком, независимо от того, кто именно пишет —
  полезно в группах, где каждый участник по отдельности укладывается в свой личный лимит, а суммарно
  чат всё равно флудится.
- Параметры `maxAttempts,decaySeconds` — обычный синтаксис `Middleware:параметры` из
  `Illuminate\Pipeline`; без параметров действуют дефолты 20 попыток / 60 секунд.

При превышении лимита middleware **не отвечает пользователю само** — бросает
`UserThrottleExceededException`/`ChatThrottleExceededException` (обе — подкласс
`ThrottleExceededException`, несёт `$context` и `$retryAfter` — сколько секунд осталось до сброса).
`Bot::dispatch()` ловит это исключение и передаёт в штатный Laravel `report()`. Пакет сам
регистрирует `dontReport(ThrottleExceededException::class)`, поэтому по умолчанию — полная тишина:
ни лога, ни ответа пользователю. Чтобы среагировать (ответить, отправить алерт, посчитать метрику) —
обычный Laravel-путь в `bootstrap/app.php`, без специального API от пакета:

```php
use Appto\TelegramBot\Exceptions\ThrottleExceededException;
use Appto\TelegramBot\Exceptions\UserThrottleExceededException;
use Illuminate\Foundation\Configuration\Exceptions;

->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->stopIgnoring(ThrottleExceededException::class); // снять тишину пакета
    $exceptions->reportable(function (UserThrottleExceededException $e): void {
        $e->context->reply('Слишком часто, подождите немного.');
    });
})
```

`$e->context` — тот же `UpdateContext`, что пришёл в middleware: доступны `->bot->id`, `->chatId()`,
`->userId()`, `->reply()` и другие методы, так что видно, какой бот/чат/юзер превысил лимит, и можно
сразу на него ответить.

## Дальше

→ [13. Webhook и long polling](13-delivery.md)

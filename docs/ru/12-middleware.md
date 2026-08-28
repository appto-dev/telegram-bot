# 12. Middleware

## 12.1 Зачем нужен

`RequiresPermission` (см. [11. Права доступа](11-permissions.md)) решает вопрос «может ли этот
конкретный пользователь выполнить именно эту команду». Middleware — для проверок и действий,
которые должны выполняться для *всех* апдейтов бота ещё до того, как фреймворк начнёт искать,
какой хендлер вызвать: капча, троттлинг частых сообщений, логирование, блокировка забаненных
пользователей, определение языка и так далее.

## 12.2 Как написать свой

```php
namespace App\ShopBot\Middleware;

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

## Дальше

→ [13. Webhook и long polling](13-delivery.md)

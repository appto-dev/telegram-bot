# 4. Quick start

Let's build a minimal bot with one `/start` command.

## 4.1 Bot class

```php
// app/GreetBot/GreetBot.php
namespace App\GreetBot;

use App\GreetBot\Commands\StartCommand;
use Appto\TelegramBot\Bot\Bot;

class GreetBot extends Bot
{
    protected function boot(): void
    {
        $this->onCommand('start', StartCommand::class);
    }
}
```

## 4.2 Command handler

```php
// app/GreetBot/Commands/StartCommand.php
namespace App\GreetBot\Commands;

use Appto\TelegramBot\Contracts\CommandHandler;
use Appto\TelegramBot\Contracts\HasDescription;
use Appto\TelegramBot\Update\UpdateContext;

class StartCommand implements CommandHandler, HasDescription
{
    public function handle(UpdateContext $context): void
    {
        $context->reply("Hi! I'm here 👋");
    }

    public static function description(): string
    {
        return 'Start using the bot';
    }
}
```

`HasDescription` is optional — it's only needed for the command to show up in `/help` (see
[5. Commands](05-commands.md)).

## 4.3 Wire it into the config

```php
// config/telegram-bot.php
'bots' => [
    'greet' => [
        'token' => env('TELEGRAM_BOT_TOKEN'),
        'webhook_secret' => env('TELEGRAM_BOT_WEBHOOK_SECRET'),
        'bot' => \App\GreetBot\GreetBot::class,
    ],
],
```

## 4.4 Run and check

```bash
php artisan telegram:poll greet
```

Send `/start` to the bot in Telegram — you should get "Hi! I'm here 👋" back. If nothing happens,
see the checklist in [16. Debugging](16-debugging.md).

When it's time to go live, swap long polling for a webhook — see
[13. Webhook and long polling](13-delivery.md).

## Next

→ [5. Commands](05-commands.md)

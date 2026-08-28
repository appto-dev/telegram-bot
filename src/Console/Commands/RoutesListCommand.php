<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Console\Commands;

use Appto\TelegramBot\Bot\BotIdentity;
use Appto\TelegramBot\Bot\BotManager;
use Appto\TelegramBot\Console\Output\ChoiceBotPrompt;
use Appto\TelegramBot\Contracts\RequiresPermission;
use Appto\TelegramBot\Routing\CommandRouter;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('telegram:routes {bot?}
    {--type= : Filter by router type (commands, callbacks, text)}')]
#[Description('Shows the routes registered by bots (commands, callback patterns, text triggers).')]
class RoutesListCommand extends Command
{
    private const array ROUTER_LABELS = [
        'command' => 'Command',
        'callback' => 'Callback',
        'text' => 'Text',
        'update' => 'Update',
    ];

    /**
     * Execute the console command.
     */
    public function handle(BotManager $botManager): int
    {
        $bot = $this->argument('bot') ?? ChoiceBotPrompt::handle($botManager);
        $identity = $botManager->findByName($bot);

        $type = $this->option('type');
        if ($type !== null && ! isset(self::ROUTER_LABELS[$type])) {
            $this->error(sprintf(
                'Unknown type "%s". Available: %s.',
                $type,
                implode(', ', array_keys(self::ROUTER_LABELS)),
            ));

            return self::FAILURE;
        }

        $this->renderBot($botManager, $identity, $type);

        return self::SUCCESS;
    }

    private function renderBot(BotManager $botManager, BotIdentity $identity, ?string $onlyType): void
    {
        $bot = $botManager->resolve($identity);

        $rows = [];

        foreach (self::ROUTER_LABELS as $routerKey => $label) {
            if ($onlyType !== null && $onlyType !== $routerKey) {
                continue;
            }

            try {
                $routes = $bot->router()->get($routerKey)->all();
                foreach ($routes as $route => $handler) {
                    $rows[] = [
                        $label,
                        $routerKey == CommandRouter::key() ? '/'.$route : $route,
                        $this->describeHandler($handler),
                        $this->isProtected($handler) ? 'yes' : '',
                    ];
                }
            } catch (\Exception $e) {
            }
        }

        $this->newLine();
        $this->line("<fg=cyan;options=bold>Handler: {$identity->handler}</>");

        if ($rows === []) {
            $this->line('  <fg=gray>no routes</>');

            return;
        }

        $this->table(['Type', 'Route', 'Handler', 'Protected'], $rows);
    }

    private function describeHandler(mixed $handler): string
    {
        return is_string($handler) ? $handler : 'Closure';
    }

    private function isProtected(mixed $handler): bool
    {
        return is_string($handler) && is_subclass_of($handler, RequiresPermission::class);
    }
}

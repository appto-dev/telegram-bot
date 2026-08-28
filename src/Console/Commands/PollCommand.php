<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Console\Commands;

use Appto\TelegramBot\Bot\Bot;
use Appto\TelegramBot\Bot\BotIdentity;
use Appto\TelegramBot\Bot\BotManager;
use Appto\TelegramBot\Client\TelegramClient;
use Appto\TelegramBot\Client\TelegramClientFactory;
use Appto\TelegramBot\Console\Output\ChoiceBotPrompt;
use Appto\TelegramBot\Console\Output\DisplayOutgoingCallInConsole;
use Appto\TelegramBot\Console\Output\DisplayPollingErrorInConsole;
use Appto\TelegramBot\Console\Output\DisplayUpdateInConsole;
use Appto\TelegramBot\Console\Output\TrafficLogger;
use Appto\TelegramBot\Console\Output\WebhookPrompts;
use Appto\TelegramBot\Events\TelegramApiCallMade;
use Appto\TelegramBot\Type\Message;
use Appto\TelegramBot\Type\Update;
use Appto\TelegramBot\Update\UpdateContext;
use Appto\TelegramBot\Update\UpdateType;
use GuzzleHttp\RequestOptions;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;

use function Laravel\Prompts\intro;

final class PollCommand extends Command
{
    protected $signature = 'telegram:poll {bot?}
        {--timeout=30 : Timeout in seconds}
        {--o|show-outgoing : Also show outgoing bot replies}
        {--only=* : Only show/log these update types (message, callback_query, ...)}
        {--user=* : Only show/log updates from these Telegram user IDs}
        {--dry-run : Receive updates without dispatching them to the bot}
        {--l|--log-traffic : Log raw incoming/outgoing payloads to storage/logs/telegram-traffic.log}';

    protected $description = 'Polls the bot for updates via long polling (dev mode, no webhook required)';

    private const int DEFAULT_SLEEP_TIMEOUT = 5;

    /**
     * Execute the console command.
     *
     * @throws BindingResolutionException
     */
    public function handle(BotManager $botManager): void
    {
        $bot = $this->argument('bot') ?? ChoiceBotPrompt::handle($botManager);
        $identity = $botManager->findByName($bot);

        /** @var TelegramClientFactory $factory */
        $factory = app(TelegramClientFactory::class);
        $client = $factory->make($identity);
        $prompt = new WebhookPrompts($client);

        if (! $prompt->ensureRemoved()) {
            return;
        }

        $bot = $botManager->resolve($identity);

        intro("Bot [{$identity->id}] is listening. Press Ctrl+C to stop.");

        $this->poll($identity, $bot, $client);
    }

    private function poll(BotIdentity $identity, Bot $bot, TelegramClient $client): void
    {
        $updateDisplay = new DisplayUpdateInConsole($this->output, $identity);
        $errorDisplay = new DisplayPollingErrorInConsole($this->output);
        $traffic = $this->option('log-traffic')
            ? new TrafficLogger(storage_path('logs/telegram-traffic.log'))
            : null;

        if ($this->option('show-outgoing')) {
            \Event::listen(
                TelegramApiCallMade::class,
                function (TelegramApiCallMade $event) use ($traffic) {
                    (new DisplayOutgoingCallInConsole($this->output))->handle($event);
                    $traffic?->outgoing($event->method, $event->response);
                },
            );
        } elseif ($traffic) {
            // separate subscription if the console doesn’t show outgoing messages, but the log is still needed
            \Event::listen(
                TelegramApiCallMade::class,
                fn (TelegramApiCallMade $event) => $traffic->outgoing($event->method, $event->response),
            );
        }

        $only = $this->option('only');
        $users = array_map('intval', $this->option('user'));
        $dryRun = $this->option('dry-run');

        $offset = 0;
        $failures = 0;

        $timeout = intval($this->option('timeout'));

        while (true) {
            try {
                $updates = $client->forNextRequest([
                    RequestOptions::TIMEOUT => $timeout + 5,
                ])->getUpdates(offset: $offset, timeout: $timeout, allowed_updates: $only);

                $failures = 0;
            } catch (\Exception $e) {
                $errorDisplay->handleFatal($e);
                report($e);

                sleep(min(60, self::DEFAULT_SLEEP_TIMEOUT * 2 ** $failures++));

                continue;
            }

            if (! $updates) {
                continue;
            }

            foreach ($updates as $update) {
                $offset = $update->update_id + 1;

                $traffic?->incoming($update);

                if (! $this->passesFilters($update, $only, $users)) {
                    continue;
                }

                try {
                    $updateDisplay->handle($update);
                } catch (\Exception $e) {
                    $errorDisplay->handleFatal($e);
                    report($e);
                }

                if ($dryRun) {
                    continue;
                }

                try {
                    $context = new UpdateContext($identity, $update);
                    $bot->dispatch($context);
                } catch (\Exception $e) {
                    $errorDisplay->handleUpdateError($e, $update);
                }
            }
        }
    }

    /**
     * @param  string[]  $only
     * @param  int[]  $users
     */
    private function passesFilters(Update $update, array $only, array $users): bool
    {
        if ($only && ! in_array(UpdateType::detect($update)->value, $only, true)) {
            return false;
        }

        if ($users && ! in_array($this->updateUserId($update), $users, true)) {
            return false;
        }

        return true;
    }

    private function updateUserId(Update $update): ?int
    {
        $type = UpdateType::detect($update);

        return match (true) {
            $type->payloadClass() instanceof Message => $update->{$type->value}->from->id,
            isset($update->{$type->value}->from) && $update->{$type->value}->from => $update->{$type->value}->from->id,
            default => null,
        };
    }
}

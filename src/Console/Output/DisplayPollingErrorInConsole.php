<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Console\Output;

use Appto\TelegramBot\Type\Update;
use Illuminate\Console\OutputStyle;
use Illuminate\Http\Client\RequestException;

final readonly class DisplayPollingErrorInConsole
{
    public function __construct(private OutputStyle $output) {}

    public function handleUpdateError(\Throwable $exception, Update $update): void
    {
        report($exception);

        $this->output->writeln(sprintf(
            '<fg=gray>[%s]</> <bg=red;fg=white> ERROR </> processing update <fg=cyan>#%d</>: %s <fg=gray>[%s]</>',
            now()->format('H:i:s'),
            $update->update_id,
            $exception->getMessage(),
            $exception->getFile().':'.$exception->getLine(),
        ));

        if ($this->output->isVerbose()) {
            $this->output->writeln('<fg=red>'.$exception->getTraceAsString().'</>');
        }
    }

    public function handleFatal(\Throwable $exception): void
    {
        $this->output->newLine();
        $this->output->writeln('<bg=red;fg=white> FATAL </> long polling stopped');
        $this->output->writeln(sprintf('<fg=red>%s: %s</>', $exception::class, $exception->getMessage()));

        if ($exception instanceof RequestException) {
            $this->output->writeln(sprintf(
                '<fg=gray>HTTP %d — %s</>',
                $exception->response->status(),
                $exception->response->body(),
            ));
        }
    }
}

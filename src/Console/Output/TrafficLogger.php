<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Console\Output;

use Appto\TelegramBot\Type\Update;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

final readonly class TrafficLogger
{
    private Logger $logger;

    public function __construct(string $path)
    {
        $handler = new StreamHandler($path, Level::Debug);
        $handler->setFormatter(new LineFormatter(
            format: "[%datetime%] %channel%.%level_name%: %message% %context%\n",
            dateFormat: 'Y-m-d H:i:s',
        ));

        $this->logger = new Logger('telegram-traffic');
        $this->logger->pushHandler($handler);
    }

    public function incoming(Update $update): void
    {
        $this->logger->debug('IN', $update->toArray());
    }

    public function outgoing(string $method, array|bool $response): void
    {
        if (! $response) {
            return;
        }
        $this->logger->debug("OUT {$method}", is_array($response) ? $response : ['success' => $response]);
    }
}

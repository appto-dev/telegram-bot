<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Bot;

use Appto\TelegramBot\Bot\ConfigBotRepository;
use Appto\TelegramBot\Tests\TestCase;

final class ConfigBotRepositoryTest extends TestCase
{
    public function test_it_skips_bots_without_a_token(): void
    {
        $repository = new ConfigBotRepository([
            'shop' => [
                'token' => 'shop-token',
                'webhook_secret' => null,
                'handler' => 'App\ShopBot\ShopBot',
            ],
            'support' => [
                'token' => null,
                'webhook_secret' => null,
                'handler' => 'App\SupportBot\SupportBot',
            ],
        ]);

        $this->assertSame(['shop'], array_keys($repository->all()));
    }
}

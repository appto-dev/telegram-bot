<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Bot;

use Appto\TelegramBot\Bot\BotIdentity;
use Appto\TelegramBot\Bot\DatabaseBotRepository;
use Appto\TelegramBot\Bot\TelegramBotModel;
use Appto\TelegramBot\Tests\TestCase;

final class DatabaseBotRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->migrateBots();
    }

    public function test_it_returns_an_empty_array_when_no_bots_exist(): void
    {
        $this->assertSame([], (new DatabaseBotRepository)->all());
    }

    public function test_it_maps_a_row_into_a_bot_identity_keyed_by_name(): void
    {
        TelegramBotModel::create([
            'name' => 'shop',
            'token' => 'shop-token',
            'webhook_secret' => 'shop-secret',
            'handler' => 'App\ShopBot\ShopBot',
            'is_active' => true,
        ]);

        $bots = (new DatabaseBotRepository)->all();

        $this->assertSame(['shop'], array_keys($bots));

        $identity = $bots['shop'];
        $this->assertInstanceOf(BotIdentity::class, $identity);
        $this->assertSame('shop', $identity->id);
        $this->assertSame('shop-token', $identity->token);
        $this->assertSame('shop-secret', $identity->webhook_secret);
        $this->assertSame('App\ShopBot\ShopBot', $identity->handler);
    }

    public function test_it_excludes_inactive_bots(): void
    {
        TelegramBotModel::create([
            'name' => 'archived',
            'token' => 'archived-token',
            'webhook_secret' => null,
            'handler' => 'App\ShopBot\ShopBot',
            'is_active' => false,
        ]);

        $this->assertSame([], (new DatabaseBotRepository)->all());
    }

    public function test_it_returns_several_bots_each_keyed_by_its_own_name(): void
    {
        TelegramBotModel::create([
            'name' => 'shop',
            'token' => 'shop-token',
            'webhook_secret' => null,
            'handler' => 'App\ShopBot\ShopBot',
            'is_active' => true,
        ]);

        TelegramBotModel::create([
            'name' => 'support',
            'token' => 'support-token',
            'webhook_secret' => null,
            'handler' => 'App\Bot\TestBot',
            'is_active' => true,
        ]);

        $bots = (new DatabaseBotRepository)->all();

        $this->assertEqualsCanonicalizing(['shop', 'support'], array_keys($bots));
        $this->assertSame('shop', $bots['shop']->id);
        $this->assertSame('support', $bots['support']->id);
    }

    /**
     * webhook_secret is nullable on the column, but the model auto-generates one on create when
     * left empty (see TelegramBotModel::boot()) — the repository must pass that generated value
     * through rather than a leftover null.
     */
    public function test_an_auto_generated_webhook_secret_is_passed_through(): void
    {
        TelegramBotModel::create([
            'name' => 'shop',
            'token' => 'shop-token',
            'webhook_secret' => null,
            'handler' => 'App\ShopBot\ShopBot',
            'is_active' => true,
        ]);

        $identity = (new DatabaseBotRepository)->all()['shop'];

        $this->assertNotNull($identity->webhook_secret);
        $this->assertSame(64, strlen($identity->webhook_secret));
    }
}

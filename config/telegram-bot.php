<?php

declare(strict_types=1);

use App\MyBot\MyBot;
use GuzzleHttp\RequestOptions;

return [
    /*
    |--------------------------------------------------------------------------
    | Bots (config driver)
    |--------------------------------------------------------------------------
    |
    | List of bots when source = "config". Used only by this driver
    | and ignored when source = "database".
    |
    | Array key — an arbitrary unique bot alias (used in webhook routes
    | and when resolving a bot by name).
    |
    */
    'bots' => [
        'default' => [
            'token' => env('TELEGRAM_BOT_TOKEN'),
            'webhook_secret' => env('TELEGRAM_BOT_WEBHOOK_SECRET'),
            'handler' => MyBot::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Bots Source
    |--------------------------------------------------------------------------
    |
    | Where the framework reads the list of bots from.
    |
    | Supported drivers: "config", "database".
    |
    | - config   — bots are described in this file, in the "bots" array.
    | - database — bots are stored in the telegram_bots table.
    |
    */
    'repository' => env('TELEGRAM_BOT_REPOSITORY', 'config'),

    /*
    |--------------------------------------------------------------------------
    | Database Repository Settings
    |--------------------------------------------------------------------------
    |
    | Only used when repository = "database".
    |
    */
    'database' => [
        /*
        | Which telegram_bots column is used as the public webhook route
        | segment (/telegram/webhook/{key}) instead of "name".
        |
        | Point this at a column you added yourself (e.g. "uuid") via your
        | own migration extending telegram_bots, if you don't want webhook
        | URLs to reveal/leak the bot's human-readable name. The package
        | does not create or manage this column — it's your migration and
        | your uniqueness constraint.
        |
        | CLI commands (telegram:poll, telegram:set-webhook, ...) are
        | unaffected — they always take the bot's "name".
        |
        | Default: "name" — identical behavior to previous versions.
        */
        'webhook_key_column' => 'name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Base URI
    |--------------------------------------------------------------------------
    |
    | Base address of the Telegram Bot API. Only worth changing when
    | working through a local Bot API Server (telegram-bot-api) instead
    | of the cloud one.
    |
    */
    'base_uri' => env('TELEGRAM_API_BASE_URI', 'https://api.telegram.org'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client
    |--------------------------------------------------------------------------
    |
    | Options passed to withOptions() of the HTTP client used for the
    | Bot API. Read by TelegramClientFactory and applied to every request.
    |
    | Full list of options: GuzzleHttp\RequestOptions.
    |
    */
    'http' => [
        RequestOptions::TIMEOUT => 30,
        RequestOptions::CONNECT_TIMEOUT => 5,
    ],

    'unauthorized' => [
        'message' => env('TELEGRAM_BOT_UNAUTHORIZED_MESSAGE'),
        'show_alert' => true,
    ],
];

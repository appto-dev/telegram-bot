# 19. Events

## 19.1 What events are for

The package dispatches a handful of plain Laravel events at points where something happens that
application code might want to react to — an update arrived, a Bot API call was made, a dialog
started, finished, or was cancelled. The package itself does nothing with them beyond dispatching
— no built-in listener, no config flag to turn them on. You subscribe with the regular Laravel
mechanism (`Event::listen()` in a service provider) and decide what to do.

This is deliberately an extension point, not a feature: rather than the package guessing what you
want to do with "a message was sent" or "a dialog finished" (log it? track it for later cleanup?
send an analytics event?), it hands you the moment and lets you write the few lines that fit your
case.

## 19.2 The events

| Event | Fires | Payload |
|---|---|---|
| `TelegramApiCallRequested` | Right before every Bot API call, from `BaseClient::call()` | `bot: BotIdentity`, `method: string`, `parameters: array` |
| `TelegramApiCallMade` | Right after every Bot API call succeeds | `bot: BotIdentity`, `method: string`, `response: array\|bool` (the raw `result` from Telegram) |
| `UpdateReceived` | At the very top of `Bot::dispatch()`, before middleware and routing | `context: UpdateContext` |
| `DialogStarted` | When a dialog starts (`DialogManager::start()`) | `context: UpdateContext`, `dialog: Dialog` |
| `DialogCompleted` | When a dialog finishes (`onComplete()` just ran) | `context: UpdateContext`, `dialog: Dialog`, `answers: array` |
| `DialogCancelled` | When a dialog is cancelled (`onCancel()` just ran) | `context: UpdateContext`, `dialog: Dialog` |

All of them live in `Appto\TelegramBot\Events\`. None of them carry a `bot` field when a
`context` is already on the payload — `$context->bot` already gives you that; only the two
`TelegramApiCall*` events (fired from `BaseClient`, which never sees an `UpdateContext`) carry
`bot` explicitly.

`TelegramApiCallRequested`/`TelegramApiCallMade` fire for **every** Bot API method, not just
`reply*()` calls — `deleteMessage`, `getMe`, anything. `response`/`parameters` are the raw arrays
Telegram sends/expects, not hydrated DTOs — `message_id` is available as
`$event->response['message_id']` when the call returns a message.

`UpdateReceived` fires unconditionally, even for updates that end up throttled or unhandled — it
answers "did an update arrive", not "was it processed".

`DialogStarted`/`DialogCompleted`/`DialogCancelled` sit alongside the existing `Dialog::onComplete()`/
`onCancel()` hooks (see [10.2](10-dialogs.md#102-what-a-dialog-is-made-of)), not instead of them —
those hooks stay the right place for a dialog's own business logic (saving to the database, etc.);
the events are for code that reacts to *any* dialog, without editing every `Dialog` subclass.

## 19.3 Listening

Register listeners the normal Laravel way, typically in `AppServiceProvider::boot()`:

```php
use Appto\TelegramBot\Events\DialogCompleted;
use Illuminate\Support\Facades\Event;

Event::listen(DialogCompleted::class, function (DialogCompleted $event): void {
    logger()->info('Dialog finished', [
        'dialog' => $event->dialog::class,
        'chat_id' => $event->context->chatId(),
        'answers' => $event->answers,
    ]);
});
```

## 19.4 Recipe: cleaning up a dialog's messages when it ends

A common reason to reach for these events: delete every message a dialog sent/received once it's
done, so the chat doesn't stay cluttered with intermediate questions. The package doesn't track
message IDs for you (see [19.1](#191-what-events-are-for)) — combine `TelegramApiCallMade` (to
collect outgoing `message_id`s) with `UpdateReceived` (for incoming ones) and
`DialogCompleted`/`DialogCancelled` (to know when to delete), keyed by chat:

```php
use Appto\TelegramBot\Events\{DialogCancelled, DialogCompleted, TelegramApiCallMade, UpdateReceived};
use Illuminate\Support\Facades\{Cache, Event};

// Collect outgoing message_ids per chat.
Event::listen(TelegramApiCallMade::class, function (TelegramApiCallMade $event): void {
    if (str_starts_with($event->method, 'send') && isset($event->response['message_id'], $event->response['chat']['id'])) {
        $key = 'dialog-messages:'.$event->response['chat']['id'];
        Cache::put($key, [...Cache::get($key, []), $event->response['message_id']], now()->addHour());
    }
});

// Collect the incoming ones.
Event::listen(UpdateReceived::class, function (UpdateReceived $event): void {
    if ($id = $event->context->message()?->message_id) {
        $key = 'dialog-messages:'.$event->context->chatId();
        Cache::put($key, [...Cache::get($key, []), $id], now()->addHour());
    }
});

// Delete them all once the dialog is done, one way or another.
$cleanup = function (DialogCompleted|DialogCancelled $event): void {
    $key = 'dialog-messages:'.$event->context->chatId();
    $ids = Cache::pull($key, []);

    if ($ids !== []) {
        $event->context->client()->deleteMessages(chat_id: $event->context->chatId(), message_ids: $ids);
    }
};

Event::listen(DialogCompleted::class, $cleanup);
Event::listen(DialogCancelled::class, $cleanup);
```

Telegram only lets a bot delete messages it can see and has rights to — its own messages almost
always, the user's incoming ones typically only in a group/supergroup where the bot is an admin
(see the Bot API's `deleteMessage` restrictions). Plan the recipe above around that: it works
reliably for private chats' bot-sent messages; treat deleting the user's own messages as
best-effort.

## Next

→ [20. Reference](20-reference.md)

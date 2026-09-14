# 19. События

## 19.1 Зачем нужны события

Пакет диспатчит несколько обычных Laravel-событий в моменты, на которые код приложения может
захотеть отреагировать — пришёл апдейт, выполнен вызов Bot API, диалог стартовал, завершился или
был отменён. Сам пакет с ними ничего не делает, кроме диспатча — нет ни встроенного слушателя, ни
конфиг-флага, который их включает. Подписываетесь обычным способом Laravel (`Event::listen()` в
сервис-провайдере) и решаете, что делать.

Это осознанно точка расширения, а не готовая фича: вместо того чтобы пакет угадывал, что вы
хотите сделать с «сообщение отправлено» или «диалог завершился» (залогировать? запомнить для
последующей очистки? отправить в аналитику?), он отдаёт вам момент, а несколько строк под свою
задачу вы пишете сами.

## 19.2 Список событий

| Событие | Когда стреляет | Payload |
|---|---|---|
| `TelegramApiCallRequested` | Прямо перед каждым вызовом Bot API, из `BaseClient::call()` | `bot: BotIdentity`, `method: string`, `parameters: array` |
| `TelegramApiCallMade` | Сразу после успешного вызова Bot API | `bot: BotIdentity`, `method: string`, `response: array\|bool` (сырой `result` от Telegram) |
| `UpdateReceived` | В самом начале `Bot::dispatch()`, до middleware и роутинга | `context: UpdateContext` |
| `DialogStarted` | При старте диалога (`DialogManager::start()`) | `context: UpdateContext`, `dialog: Dialog` |
| `DialogCompleted` | При завершении диалога (только что отработал `onComplete()`) | `context: UpdateContext`, `dialog: Dialog`, `answers: array` |
| `DialogCancelled` | При отмене диалога (только что отработал `onCancel()`) | `context: UpdateContext`, `dialog: Dialog` |

Все они живут в `Appto\TelegramBot\Events\`. Ни у одного нет поля `bot`, если в payload и так
есть `context` — `$context->bot` уже даёт то же самое; явное поле `bot` есть только у двух
`TelegramApiCall*`-событий (они летят из `BaseClient`, который `UpdateContext` вообще не видит).

`TelegramApiCallRequested`/`TelegramApiCallMade` стреляют на **любой** метод Bot API, а не только
на `reply*()` — `deleteMessage`, `getMe`, что угодно. `response`/`parameters` — сырые массивы,
которые Telegram присылает/ожидает, а не готовые DTO — `message_id` достаётся как
`$event->response['message_id']`, когда вызов возвращает сообщение.

`UpdateReceived` стреляет безусловно, даже для апдейтов, которые в итоге затроттлены или ничем не
обработаны — он отвечает на вопрос «апдейт пришёл», а не «апдейт обработан».

`DialogStarted`/`DialogCompleted`/`DialogCancelled` существуют рядом с уже знакомыми хуками
`Dialog::onComplete()`/`onCancel()` (см. [10.2](10-dialogs.md#102-what-a-dialog-is-made-of)), а не
вместо них — хуки остаются правильным местом для бизнес-логики конкретного диалога (запись в БД
и т. п.); события — для кода, который реагирует на *любой* диалог, не трогая каждый класс
`Dialog`.

## 19.3 Подписка

Регистрируете слушателей обычным для Laravel способом, как правило в `AppServiceProvider::boot()`:

```php
use Appto\TelegramBot\Events\DialogCompleted;
use Illuminate\Support\Facades\Event;

Event::listen(DialogCompleted::class, function (DialogCompleted $event): void {
    logger()->info('Диалог завершён', [
        'dialog' => $event->dialog::class,
        'chat_id' => $event->context->chatId(),
        'answers' => $event->answers,
    ]);
});
```

## 19.4 Рецепт: очистка сообщений диалога по завершению

Частая причина обратиться к этим событиям — удалить все сообщения, которые диалог
отправил/получил, когда он завершится, чтобы чат не оставался захламлён промежуточными вопросами.
Пакет сам ID сообщений не копит (см. [19.1](#191-зачем-нужны-события)) — совместите
`TelegramApiCallMade` (собрать исходящие `message_id`) с `UpdateReceived` (входящие) и
`DialogCompleted`/`DialogCancelled` (понять, когда удалять), с привязкой по чату:

```php
use Appto\TelegramBot\Events\{DialogCancelled, DialogCompleted, TelegramApiCallMade, UpdateReceived};
use Illuminate\Support\Facades\{Cache, Event};

// Копим исходящие message_id по чату.
Event::listen(TelegramApiCallMade::class, function (TelegramApiCallMade $event): void {
    if (str_starts_with($event->method, 'send') && isset($event->response['message_id'], $event->response['chat']['id'])) {
        $key = 'dialog-messages:'.$event->response['chat']['id'];
        Cache::put($key, [...Cache::get($key, []), $event->response['message_id']], now()->addHour());
    }
});

// Копим входящие.
Event::listen(UpdateReceived::class, function (UpdateReceived $event): void {
    if ($id = $event->context->message()?->message_id) {
        $key = 'dialog-messages:'.$event->context->chatId();
        Cache::put($key, [...Cache::get($key, []), $id], now()->addHour());
    }
});

// Удаляем всё разом, как только диалог завершился — неважно, как именно.
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

Telegram разрешает боту удалять только те сообщения, которые он видит и на которые у него есть
права — свои сообщения почти всегда, а входящие от пользователя обычно только в группе/супергруппе,
где бот — админ (см. ограничения `deleteMessage` в Bot API). Планируйте рецепт выше с оглядкой на
это: он надёжно работает для сообщений бота в приватных чатах; удаление собственных сообщений
пользователя считайте best-effort.

## Далее

→ [20. Справочник](20-reference.md)

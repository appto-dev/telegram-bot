# 6. Buttons and callback queries

## 6.1 What a callback query is

An inline button (a button under the message, as opposed to a reply-keyboard button under the
input field) doesn't send text into the chat when tapped — Telegram sends the bot a
`callback_query` carrying the `callback_data` value you set when building the button. The user
doesn't type anything.

## 6.2 Registering with parameters

```php
$this->onCallback('order:confirm {id}', OrderConfirmHandler::class);
```

Curly braces mark named parameters that the framework extracts from `callback_data` using the
pattern and passes into the handler. For example, a button with
`callback_data = 'order:confirm 42'` calls `OrderConfirmHandler` with `id = '42'`.

```php
class OrderConfirmHandler implements CallbackHandler
{
    public function handle(UpdateContext $context, string $id): void
    {
        // confirm order #$id
        $context->answerCallbackQuery();
    }
}
```

As with commands, the handler can be a class (`CallbackHandler`) or a callable.

## 6.3 Don't forget `answerCallbackQuery()`

Until the bot calls `answerCallbackQuery()`, the button stays in a "loading" state in the user's
Telegram client, and eventually the client shows a timeout error. Call it in every callback
handler — even with no arguments, just `$context->answerCallbackQuery()` — as soon as you're done
handling the tap.

The same method can show a toast or an alert:

```php
$context->answerCallbackQuery(text: 'Order confirmed', show_alert: true);
```

## 6.4 Building the buttons themselves

Inline keyboard layout with `callback_data` is covered in [8. Keyboards](08-keyboards.md).

## Next

→ [7. Plain text and triggers](07-text-and-buttons.md)

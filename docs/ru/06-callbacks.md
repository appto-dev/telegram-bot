# 6. Кнопки и callback-запросы

## 6.1 Что такое callback-запрос

Инлайн-кнопка (кнопка под сообщением, в отличие от reply-клавиатуры под полем ввода) при нажатии
не отправляет текст в чат — Telegram присылает боту `callback_query` со значением `callback_data`,
которое вы сами задали при создании кнопки. Пользователь при этом ничего не печатает.

## 6.2 Регистрация с параметрами

```php
$this->onCallback('order:confirm {id}', OrderConfirmHandler::class);
```

Фигурные скобки — именованные параметры, которые фреймворк выделит из `callback_data` по паттерну
и передаст в хендлер. Например, кнопка с `callback_data = 'order:confirm 42'` вызовет
`OrderConfirmHandler` с `id = '42'`.

```php
class OrderConfirmHandler implements CallbackHandler
{
    public function handle(UpdateContext $context, string $id): void
    {
        // подтверждаем заказ №$id
        $context->answerCallbackQuery();
    }
}
```

Как и с командами, хендлером может быть класс (`CallbackHandler`) или callable.

## 6.3 Не забывайте `answerCallbackQuery()`

Пока бот не вызовет `answerCallbackQuery()`, кнопка в интерфейсе Telegram у пользователя
продолжает «крутиться» (показывает состояние загрузки), а через некоторое время клиент покажет
ошибку по таймауту. Вызывайте его в каждом callback-хендлере — даже без текста, простым
`$context->answerCallbackQuery()`, — как только логика обработки нажатия завершена.

Через тот же метод можно показать всплывающее уведомление или алерт:

```php
$context->answerCallbackQuery(text: 'Заказ подтверждён', show_alert: true);
```

## 6.4 Создание самих кнопок

Разметка инлайн-клавиатуры с `callback_data` разбирается в [8. Клавиатуры](08-keyboards.md).

## Дальше

→ [7. Обычный текст и триггеры](07-text-and-buttons.md)

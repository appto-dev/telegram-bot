# 8. Клавиатуры

Разметка клавиатур — это обычные DTO из Bot API (пакет `appto-team/telegram-bot-cast-laravel`),
а не собственный «билдер» фреймворка. Создаются через `X::from([...])`.

## 8.1 Reply-клавиатура (кнопки под полем ввода)

```php
use Appto\TelegramBot\Type\KeyboardButton;
use Appto\TelegramBot\Type\ReplyKeyboardMarkup;

ReplyKeyboardMarkup::from([
    'keyboard' => [
        [
            KeyboardButton::from(['text' => '♻️ Каталог']),
            KeyboardButton::from(['text' => '⚙️ Мой кабинет']),
        ],
    ],
    'is_persistent' => true,
    'resize_keyboard' => true,
]);
```

Нажатие такой кнопки приходит как обычное текстовое сообщение — обрабатывается через `onText()`
(см. [7. Обычный текст и триггеры](07-text-and-buttons.md)).

## 8.2 Inline-клавиатура (кнопки под сообщением)

```php
use Appto\TelegramBot\Type\InlineKeyboardButton;
use Appto\TelegramBot\Type\InlineKeyboardMarkup;

InlineKeyboardMarkup::from([
    'inline_keyboard' => [
        [
            InlineKeyboardButton::from([
                'text' => 'Подтвердить',
                'callback_data' => 'order:confirm 42',
            ]),
        ],
    ],
]);
```

Нажатие приходит как `callback_query` — обрабатывается через `onCallback()` (см.
[6. Кнопки и callback-запросы](06-callbacks.md)). Значение `callback_data` должно совпадать с
паттерном, который вы зарегистрировали в `onCallback()`.

Передаётся при отправке сообщения:

```php
$context->reply('Подтвердите заказ', reply_markup: $keyboard);
```

## 8.3 Убрать клавиатуру / принудительный ответ

```php
use Appto\TelegramBot\Type\ReplyKeyboardRemove;
use Appto\TelegramBot\Type\ForceReply;

$context->reply('Клавиатура скрыта', reply_markup: ReplyKeyboardRemove::from(['remove_keyboard' => true]));
$context->reply('Ответьте на это сообщение', reply_markup: ForceReply::from(['force_reply' => true]));
```

## 8.4 Рекомендация — выносить в отдельный класс

Как и с любой другой разметкой, разросшуюся клавиатуру лучше держать в собственном классе рядом
с константами текста кнопок (пример — в [7.3](07-text-and-buttons.md#73-пример-с-reply-клавиатурой)).
Это даёт единственный источник правды: изменили текст кнопки — не забыли поправить триггер.

## Дальше

→ [9. Ответы пользователю](09-replies.md)

# 9. Ответы пользователю

## 9.1 Простой текстовый ответ

Внутри любого хендлера или шага диалога доступен объект контекста апдейта (`UpdateContext`),
у которого есть методы `reply*()` — они сами определяют текущий чат (а для сообщений из
business-аккаунта — и нужное business-подключение), поэтому `chat_id` указывать вручную не нужно:

```php
public function handle(UpdateContext $context): void
{
    $context->reply('Готово!');
}
```

## 9.2 Другие типы ответов

Помимо текста, доступны все основные типы сообщений Bot API — с тем же принципом «текущий чат
подставляется автоматически»:

```php
$context->replyPhoto($file, caption: 'Фото товара');
$context->replyDocument($file);
$context->replyVideo($file);
$context->replyVoice($file);
$context->replyMediaGroup([$file1, $file2]);       // альбом
$context->replyLocation(latitude: 55.75, longitude: 37.62);
$context->replyContact(phone_number: '+7...', first_name: 'Иван');
$context->replyPoll(question: 'Нравится сервис?', options: [...]);
$context->replyDice();                              // кубик/дартс/слот-машина
$context->replyChatAction(ChatAction::Typing);       // «печатает…»
```

Файл для отправки — объект `FileInput`, поддерживает несколько источников:

```php
use Appto\TelegramBot\Client\FileInput;

FileInput::fromFile('/path/to/file.jpg');
FileInput::fromContent($binaryString, 'file.jpg');
FileInput::fromResource($resource, 'file.jpg');
```

## 9.3 Когда нужно больше — полный доступ к Bot API

Все `reply*()`-методы — это удобный частный случай методов `send*` из полного клиента Bot API.
Если нужен метод, для которого нет `reply*()`-обёртки, либо нужно явно указать чат, отличный от
текущего (например, отправить уведомление администратору в другой чат), используйте клиент
напрямую:

```php
$context->client()->sendMessage(chat_id: $adminChatId, text: 'Новый заказ!');
```

Через `$context->client()` доступен весь Bot API — все методы `send*`/`edit*`/`delete*`/`answer*`
и так далее, один в один соответствующие официальной документации Telegram Bot API.

## 9.4 Особый случай — чек-листы

`replyChecklist()` работает только внутри сообщений от business-аккаунта (Bot API требует
`business_connection_id` для чек-листов) — вызов в обычном чате завершится понятной ошибкой,
а не тихим сбоем.

## Дальше

→ [10. Диалоги](10-dialogs.md)

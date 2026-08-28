# 9. Replying to users

## 9.1 A simple text reply

Inside any handler or dialog step you have access to the update context object (`UpdateContext`),
which offers `reply*()` methods — they figure out the current chat on their own (and, for messages
from a business account, the right business connection too), so you never pass `chat_id` manually:

```php
public function handle(UpdateContext $context): void
{
    $context->reply('Done!');
}
```

## 9.2 Other reply types

Beyond plain text, all the main Bot API message types are available, following the same
"current chat is inferred" principle:

```php
$context->replyPhoto($file, caption: 'Product photo');
$context->replyDocument($file);
$context->replyVideo($file);
$context->replyVoice($file);
$context->replyMediaGroup([$file1, $file2]);       // album
$context->replyLocation(latitude: 55.75, longitude: 37.62);
$context->replyContact(phone_number: '+1...', first_name: 'John');
$context->replyPoll(question: 'Like the service?', options: [...]);
$context->replyDice();                              // dice/darts/slot machine
$context->replyChatAction(ChatAction::Typing);       // "typing…"
```

Files to send are `FileInput` objects, with several sources:

```php
use Appto\TelegramBot\Client\FileInput;

FileInput::fromFile('/path/to/file.jpg');
FileInput::fromContent($binaryString, 'file.jpg');
FileInput::fromResource($resource, 'file.jpg');
```

## 9.3 When you need more — full Bot API access

All `reply*()` methods are a convenient special case of the `send*` methods on the full Bot API
client. If you need a method that has no `reply*()` wrapper, or need to explicitly target a chat
other than the current one (e.g. notifying an admin in a different chat), use the client directly:

```php
$context->client()->sendMessage(chat_id: $adminChatId, text: 'New order!');
```

`$context->client()` exposes the entire Bot API — every `send*`/`edit*`/`delete*`/`answer*` method
and so on, matching the official Telegram Bot API documentation one-to-one.

## 9.4 A special case — checklists

`replyChecklist()` only works inside messages from a business account (the Bot API requires
`business_connection_id` for checklists) — calling it in a regular chat fails with a clear error
instead of silently misbehaving.

## Next

→ [10. Dialogs](10-dialogs.md)

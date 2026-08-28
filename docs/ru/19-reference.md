# 19. Справочник

## 19.1 Ключи конфига (`config/telegram-bot.php`)

| Ключ | Назначение |
|---|---|
| `bots` | Список ботов при `repository = config`: алиас → `token`, `webhook_secret`, `bot` (класс) |
| `repository` | Источник списка ботов: `config` или `database` (env `TELEGRAM_BOT_REPOSITORY`) |
| `base_uri` | Базовый адрес Bot API (менять только для локального Bot API Server) |
| `http` | Опции HTTP-клиента (Guzzle `RequestOptions`) — таймауты и т. п. |
| `unauthorized.message` | Сообщение по умолчанию при отказе в доступе (env `TELEGRAM_BOT_UNAUTHORIZED_MESSAGE`) |
| `unauthorized.show_alert` | Показывать ли отказ как алерт для callback-запросов |

## 19.2 Artisan-команды

| Команда | Назначение |
|---|---|
| `telegram:poll {bot?}` | Long polling для разработки. Флаги: `--timeout`, `-o/--show-outgoing`, `--only=*`, `--user=*`, `--dry-run`, `-l/--log-traffic` |
| `telegram:set-webhook {bot}` | Установить вебхук |
| `telegram:delete-webhook {bot}` | Снять вебхук |
| `telegram:routes {bot?} [--type=commands\|callbacks\|text]` | Список зарегистрированных маршрутов |

## 19.3 Глоссарий

- **Команда (Command)** — сообщение вида `/name`, обрабатывается через `onCommand()`.
- **Callback (Callback)** — нажатие инлайн-кнопки, приходит как `callback_query`, обрабатывается
  через `onCallback()`.
- **Текстовый триггер** — точное совпадение текста сообщения, обрабатывается через `onText()`.
- **Диалог (Dialog)** — многошаговый сценарий с сохранением состояния между сообщениями.
- **Шаг (Step)** — один вопрос-ответ внутри диалога.
- **Middleware** — код, выполняемый для каждого апдейта бота до маршрутизации.
- **Роутер (Router)** — внутренний механизм сопоставления апдейта зарегистрированному хендлеру
  (по команде/callback-паттерну/тексту).
- **Контекст апдейта (`UpdateContext`)** — объект с данными входящего апдейта и методами `reply*()`
  для ответа в текущий чат.

## Смотрите также

- [1. Введение](01-introduction.md)
- [3. Как устроена разработка бота](03-development-philosophy.md)
- [17. Рецепты](17-recipes.md)

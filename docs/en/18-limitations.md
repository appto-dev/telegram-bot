# 18. Known limitations

## 18.1 `onText()` — exact match only

Text triggers are compared to the message verbatim, case-insensitively and ignoring
leading/trailing whitespace, with no regex or wildcard support. For "similar" phrases or
free-text parsing, use a command with an argument or a dialog step instead of `onText()`.

## 18.2 One active dialog per user at a time

A given (bot, chat, user) triple can only have one active dialog at once. Starting a second dialog
while the first hasn't finished/been cancelled switches the user to the new scenario
(`StepResult::switchTo()`) — they don't run in parallel.

## 18.3 What to check when upgrading the Bot API version

Telegram regularly updates the Bot API. The package keeps its client up to date — when you upgrade
dependencies, check both the Bot API release changelog and the package's own CHANGELOG so you don't
miss new required parameters on methods you're already calling.

## Next

→ [19. Reference](19-reference.md)

# 10. Dialogs

## 10.1 When you need one

A dialog is needed when the user's answer must be remembered *across messages* — i.e. the scenario
doesn't fit into a single message. The classic example is registration: ask for a name first, wait
for the reply, then ask for an email, wait again, and only then save everything.

If all the input fits into one command with arguments or a single button tap, a dialog is
overkill — a plain command or callback handler is enough (see
[3.4](03-development-philosophy.md#34-what-to-use-a-command-a-button-or-a-dialog)).

## 10.2 What a dialog is made of

A dialog is a subclass of `Dialog` that lists its steps in order:

```php
final class RegistrationDialog extends Dialog
{
    public function steps(): array
    {
        return [
            'name' => AskName::class,
            'gender' => AskGender::class,
            'email' => AskEmail::class,
        ];
    }

    public function onComplete(UpdateContext $context, array $answers): void
    {
        // $answers — every step's data, keyed by step name
        $context->reply('Registration complete 🎉');
    }

    public function onCancel(UpdateContext $context): void
    {
        $context->reply('Registration canceled');
    }
}
```

Each step is a class implementing `Step`, with two methods: `enter()` — what to show the user when
they land on the step, and `handle()` — what to do with their reply:

```php
class AskName implements Step
{
    public function enter(DialogContext $context): void
    {
        $context->update->reply('What is your name?');
    }

    public function handle(DialogContext $context): StepResult
    {
        $text = $context->update->message()?->text;

        if (empty($text)) {
            $context->update->reply('Name is required, please try again');

            return StepResult::repeat();
        }

        return StepResult::next($text);
    }
}
```

`DialogContext` inside a step gives you access to the regular `UpdateContext` (`$context->update`),
to previous steps' answers (`$context->answer('name')`), and to the current step's name.

## 10.3 Registering a dialog

A dialog is registered exactly like any other handler — as a command, a callback, or a text
trigger:

```php
$this->onCommand('start', RegistrationDialog::class);
// or
$this->onCallback('dialog:login', LoginDialog::class);
```

There's no separate "dialog registry" — a dialog starts the moment the framework reaches it as a
regular command/button/text handler.

## 10.4 What a step returns

A step's `handle()` returns a `StepResult`, which decides what happens next:

| Factory | What it does |
|---|---|
| `StepResult::next($data)` | Save `$data` and move to the next step in `steps()`'s order |
| `StepResult::back()` | Go back to the previous step |
| `StepResult::goto('stepName', $data)` | Jump to an arbitrary step by name (not necessarily adjacent) |
| `StepResult::repeat()` | Stay on the same step (e.g. after invalid input) |
| `StepResult::complete($data)` | Finish the dialog — `onComplete()` runs with every answer |
| `StepResult::cancel()` | Cancel the dialog — `onCancel()` runs |
| `StepResult::restart()` | Start the dialog over from the first step |
| `StepResult::switchTo(OtherDialog::class)` | Switch to a different dialog entirely |

`next()` on the last step and `back()` on the first are a logic error (there's no next/previous
step) — that step should return `complete()`/`cancel()` instead.

## 10.5 The data you end up with

`onComplete()` receives an `$answers` array keyed by step name (as in `steps()`), with each value
being whatever that step returned via `next()`/`complete()`. This is the only place you should
write to the database or run final business logic — not earlier, so you don't end up saving
partial data if the user cancels mid-way.

## 10.6 Cancelling a dialog

The built-in `/cancel` command cancels the active dialog:

```php
use Appto\TelegramBot\Dialog\CancelCommand;

$this->onCommand('cancel', CancelCommand::class);
```

On top of that, **any** command typed mid-dialog cancels it automatically (`onCancel()` runs), and
the command itself is then processed normally — so users never get "stuck" in a dialog if they
change their mind and type `/start` again.

## 10.7 Common mistakes

- Forgetting `back()` doesn't exist on the first step / `next()` doesn't exist on the last —
  causes a runtime error.
- Writing to the database inside `Step::handle()` instead of `Dialog::onComplete()` — if the user
  cancels, you're left with partially saved data.
- Not validating input in `handle()` before calling `next()` — an empty/invalid answer should lead
  to `repeat()` with a clear message, not sail through as-is.

## Next

→ [11. Permissions](11-permissions.md)

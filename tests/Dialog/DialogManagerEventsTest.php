<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Tests\Dialog;

use Appto\TelegramBot\Dialog\DialogManager;
use Appto\TelegramBot\Events\DialogCancelled;
use Appto\TelegramBot\Events\DialogCompleted;
use Appto\TelegramBot\Events\DialogStarted;
use Appto\TelegramBot\Tests\Fixtures\Dialogs\RecordingDialog;
use Appto\TelegramBot\Tests\Support\UpdateFactory;
use Appto\TelegramBot\Tests\TestCase;
use Illuminate\Support\Facades\Event;

final class DialogManagerEventsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->migrateDialogStates();
    }

    public function test_start_dispatches_dialog_started(): void
    {
        Event::fake([DialogStarted::class]);

        $bot = UpdateFactory::bot();
        $context = UpdateFactory::context(UpdateFactory::textMessage('/start'), $bot);

        $this->app->make(DialogManager::class)->start($context, new RecordingDialog);

        Event::assertDispatched(
            DialogStarted::class,
            fn (DialogStarted $event) => $event->context === $context
                && $event->context->bot === $bot
                && $event->dialog instanceof RecordingDialog,
        );
    }

    public function test_completing_a_dialog_dispatches_dialog_completed_with_the_answers(): void
    {
        Event::fake([DialogCompleted::class]);

        $bot = UpdateFactory::bot();
        $manager = $this->app->make(DialogManager::class);

        $manager->start(UpdateFactory::context(UpdateFactory::textMessage('/start'), $bot), new RecordingDialog);
        $manager->handle(UpdateFactory::context(UpdateFactory::textMessage('my answer'), $bot));

        Event::assertDispatched(
            DialogCompleted::class,
            fn (DialogCompleted $event) => $event->context->bot === $bot
                && $event->dialog instanceof RecordingDialog
                && $event->answers === ['ask' => 'my answer'],
        );
    }

    public function test_a_command_during_a_dialog_dispatches_dialog_cancelled(): void
    {
        Event::fake([DialogCancelled::class]);

        $bot = UpdateFactory::bot();
        $manager = $this->app->make(DialogManager::class);

        $manager->start(UpdateFactory::context(UpdateFactory::textMessage('/start'), $bot), new RecordingDialog);
        $manager->handle(UpdateFactory::context(UpdateFactory::command('cancel'), $bot));

        Event::assertDispatched(
            DialogCancelled::class,
            fn (DialogCancelled $event) => $event->context->bot === $bot
                && $event->dialog instanceof RecordingDialog,
        );
    }
}

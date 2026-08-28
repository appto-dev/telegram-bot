<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Dialog;

use Appto\TelegramBot\Update\UpdateContext;

final readonly class DialogManager
{
    public function __construct(private DialogStateRepository $repository) {}

    public function start(UpdateContext $context, Dialog $dialog): void
    {
        $startAtStep = $dialog->firstStep();

        $state = DialogState::from([
            'botName' => $context->bot->id,
            'chatId' => $context->chatId(),
            'userId' => $context->userId(),
            'handler' => get_class($dialog),
            'step' => $startAtStep,
        ]);

        $this->repository->save($state);

        $this->enterStep($dialog, $state, $context);
    }

    public function restart(UpdateContext $context, DialogState $state): void
    {
        $this->start($context, app($state->handler));
    }

    public function cancel(UpdateContext $context): void
    {
        $dialogState = $this->repository->find($context->bot->id, $context->chatId(), $context->userId());
        if (! $dialogState) {
            return;
        }

        /** @var Dialog $dialog */
        $dialog = app($dialogState->handler);
        $dialog->onCancel($context);

        $this->repository->delete($context->bot->id, $context->chatId(), $context->userId());
    }

    public function isActive(UpdateContext $context): bool
    {
        return $this->repository->find($context->bot->id, $context->chatId(), $context->userId()) !== null;
    }

    public function handle(UpdateContext $context): bool
    {
        $chatId = $context->chatId();
        $userId = $context->userId();

        if (! $chatId || ! $userId) {
            return false;
        }

        $dialogState = $this->repository->find($context->bot->id, $chatId, $userId);
        if (! $dialogState) {
            return false;
        }

        /** @var Dialog $dialog */
        $dialog = app($dialogState->handler);

        if ($context->isCommand()) {
            $this->cancel($context);

            return false;
        }

        $step = $dialog->resolveStep($dialogState->step);

        $dialogContext = new DialogContext(
            bot: $context->bot,
            update: $context,
            step: $dialogState->step,
            answers: $dialogState->answers,
        );

        $result = $step->handle($dialogContext);

        $this->applyResult($dialog, $dialogState, $step, $result, $context);

        return true;
    }

    private function applyResult(
        Dialog $dialog,
        DialogState $state,
        Step $step,
        StepResult $result,
        UpdateContext $context,
    ): void {
        $nameStep = $dialog->resolveNameStep($step);
        $state = $state->withMergedAnswers($nameStep, $result->data);

        match ($result->action) {
            StepAction::BACK => $this->moveTo($dialog, $state, $dialog->previousStepBefore($nameStep)
                ?? throw new \LogicException('Step ['.$nameStep.'] has no previous step — return restart().'), $context),
            StepAction::NEXT => $this->moveTo($dialog, $state, $dialog->nextStepAfter($nameStep)
                ?? throw new \LogicException('Step ['.$nameStep.'] has no next step — return complete() instead of next().'), $context),
            StepAction::GO_TO => $this->moveTo($dialog, $state, $result->gotoStep, $context),
            StepAction::REPEAT => $this->repository->save($state),
            StepAction::COMPLETE => $this->complete($dialog, $state, $context),
            StepAction::CANCEL => $this->cancel($context),
            StepAction::SWITCH_TO => $this->start($context, app($result->dialogClass)),
            StepAction::RESTART => $this->start($context, app($state->handler)),
        };
    }

    private function moveTo(Dialog $dialog, DialogState $state, string $stepName, UpdateContext $update): void
    {
        $state = $state->withStep($stepName);
        $this->repository->save($state);

        $this->enterStep($dialog, $state, $update);
    }

    private function complete(Dialog $dialog, DialogState $state, UpdateContext $context): void
    {
        $dialog->onComplete($context, $state->answers);
        $this->repository->delete($context->bot->id, $state->chatId, $state->userId);
    }

    private function enterStep(Dialog $dialog, DialogState $state, UpdateContext $update): void
    {
        $step = $dialog->resolveStep($state->step);

        $context = new DialogContext(
            bot: $update->bot,
            update: $update,
            step: $state->step,
            answers: $state->answers,
        );

        $step->enter($context);
    }
}

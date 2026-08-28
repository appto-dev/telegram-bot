<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Dialog;

interface Step
{
    /**
     * Вызывается при первом входе на шаг — тут отправляется вопрос/клавиатура.
     * Ничего не возвращает: шаг просто "спрашивает" и ждёт следующий апдейт.
     */
    public function enter(DialogContext $context): void;

    /**
     * Вызывается при получении ответа пользователя на этот шаг
     * (текстовое сообщение либо callback_query — тип отличается через $context->update).
     */
    public function handle(DialogContext $context): StepResult;
}

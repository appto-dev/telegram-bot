<?php

declare(strict_types=1);

namespace Appto\TelegramBot\Dialog;

enum StepAction: string
{
    case BACK = 'BACK';
    case NEXT = 'NEXT';
    case GO_TO = 'GO_TO';
    case REPEAT = 'REPEAT'; // остаться на текущем шаге (например, невалидный ввод)
    case COMPLETE = 'COMPLETE';
    case CANCEL = 'CANCEL';
    case SWITCH_TO = 'SWITCH_TO';
    case RESTART = 'RESTART';
}

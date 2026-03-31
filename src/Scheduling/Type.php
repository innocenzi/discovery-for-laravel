<?php

declare(strict_types=1);

namespace Discovery\Scheduling;

enum Type
{
    case JOB;
    case COMMAND;
    case CALL;
}

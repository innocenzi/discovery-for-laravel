<?php

declare(strict_types=1);

namespace Innocenzi\Discovery\Scheduling;

enum Type
{
    case JOB;
    case COMMAND;
    case CALL;
}

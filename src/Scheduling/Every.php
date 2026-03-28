<?php

declare(strict_types=1);

namespace Innocenzi\Discovery\Scheduling;

/**
 * @mago-expect lint:too-many-enum-cases
 */
enum Every
{
    case SECOND;
    case FIVE_SECONDS;
    case TEN_SECONDS;
    case FIFTEEN_SECONDS;
    case THIRTY_SECONDS;
    case MINUTE;
    case TWO_MINUTES;
    case THREE_MINUTES;
    case FOUR_MINUTES;
    case FIVE_MINUTES;
    case TEN_MINUTES;
    case FIFTEEN_MINUTES;
    case THIRTY_MINUTES;
    case HOUR;
    case TWO_HOURS;
    case THREE_HOURS;
    case FOUR_HOURS;
    case SIX_HOURS;
    case ODD_HOUR;
    case DAY;
    case WEEK;
    case MONTH;
    case YEAR;
}

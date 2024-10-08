<?php

namespace App\Enums;

enum StripeRecurringPeriods: string
{
    case DAY = 'day';

    case WEEK = 'week';

    case MONTH = 'month';

    case YEAR = 'year';
}

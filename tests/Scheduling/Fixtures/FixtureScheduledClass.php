<?php

declare(strict_types=1);

namespace Tests\Scheduling\Fixtures;

use Discovery\Scheduling\Every;
use Discovery\Scheduling\Schedule;

#[Schedule(Every::THIRTY_MINUTES, name: 'class_thirty_minutes')]
final class FixtureScheduledClass
{
    public function __invoke(): void
    {
    }
}

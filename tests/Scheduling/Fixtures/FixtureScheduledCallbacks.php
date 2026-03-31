<?php

declare(strict_types=1);

namespace Tests\Scheduling\Fixtures;

use Discovery\Scheduling\Every;
use Discovery\Scheduling\Schedule;

final class FixtureScheduledCallbacks
{
    #[Schedule(Every::SECOND, name: 'second')]
    public function second(): void
    {
    }

    #[Schedule(Every::HOUR, name: 'hourly')]
    public function hourly(): void
    {
    }

    #[Schedule(Every::HOUR, name: 'hourly_at', time: '15')]
    public function hourly_at(): void
    {
    }

    #[Schedule(Every::DAY, name: 'daily')]
    public function daily(): void
    {
    }

    #[Schedule(Every::DAY, name: 'daily_at', time: '13:30')]
    public function daily_at(): void
    {
    }
}

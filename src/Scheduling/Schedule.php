<?php

namespace Innocenzi\Discovery\Scheduling;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Schedule
{
    /**
     * @param string|Every $schedule A cron or {@see Every} instance that defines the schedule.
     * @param null|string $name The name of the schedule as it should appear in `schedule:list`
     * @param null|string $time The time or offset that should be specified to `hourlyAt` or `dailyAt`.
     * @param null|bool $withoutOverlapping Whether the schedule should be prevented from overlapping.
     * @param null|bool $onOneServer Whether the schedule should only run on one server in a multi-server environment.
     * @param null|bool $runInBackground Whether the schedule should run in the background.
     */
    public function __construct(
        public readonly string|Every $schedule,
        public readonly ?string $name = null,
        public readonly ?string $time = null,
        public readonly ?bool $withoutOverlapping = null,
        public readonly ?bool $onOneServer = null,
        public readonly ?bool $runInBackground = null,
    ) {}
}

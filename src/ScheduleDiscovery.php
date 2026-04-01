<?php

declare(strict_types=1);

namespace Discovery;

use Discovery\Scheduling\DiscoveredSchedule;
use Discovery\Scheduling\Every;
use Discovery\Scheduling\Schedule;
use Discovery\Scheduling\Type;
use Illuminate\Console\Scheduling\CallbackEvent;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\PendingEventAttributes;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schedule as Scheduler;
use Tempest\Discovery\Discovery;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Discovery\IsDiscovery;
use Tempest\Reflection\ClassReflector;

/**
 * @mago-expect lint:halstead
 * @mago-expect lint:kan-defect
 */
final class ScheduleDiscovery implements Discovery
{
    use IsDiscovery;

    public function __construct(
        private readonly Application $application,
    ) {}

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        $attribute = $class->getAttribute(Schedule::class);

        if ($attribute) {
            $this->discoveryItems->add($location, DiscoveredSchedule::fromClass($class, $attribute));
        }

        foreach ($class->getPublicMethods() as $method) {
            $attribute = $method->getAttribute(Schedule::class);

            if (! $attribute) {
                continue;
            }

            $this->discoveryItems->add($location, DiscoveredSchedule::fromMethod($method, $attribute));
        }
    }

    public function apply(): void
    {
        $this->application->booted(function () {
            /** @var DiscoveredSchedule $schedule */
            foreach ($this->discoveryItems as $schedule) {
                $scheduler = $this->createSchedule($schedule);

                if ($schedule->name) {
                    $scheduler->name($schedule->name);
                }

                if ($schedule->withoutOverlapping !== null) {
                    $scheduler->withoutOverlapping();
                }

                if ($schedule->onOneServer !== null) {
                    $scheduler->onOneServer();
                }

                if ($schedule->runInBackground) {
                    $scheduler->runInBackground();
                }

                if (count($schedule->when) > 0) {
                    foreach ($schedule->when as $condition) {
                        $scheduler->when($condition);
                    }
                }

                match ($schedule->schedule) {
                    Every::SECOND => $scheduler->everySecond(),
                    Every::TWO_SECONDS => $scheduler->everyTwoSeconds(),
                    Every::FIVE_SECONDS => $scheduler->everyFiveSeconds(),
                    Every::TEN_SECONDS => $scheduler->everyTenSeconds(),
                    Every::THIRTY_SECONDS => $scheduler->everyThirtySeconds(),
                    Every::MINUTE => $scheduler->everyMinute(),
                    Every::TWO_MINUTES => $scheduler->everyTwoMinutes(),
                    Every::THREE_MINUTES => $scheduler->everyThreeMinutes(),
                    Every::FOUR_MINUTES => $scheduler->everyFourMinutes(),
                    Every::FIVE_MINUTES => $scheduler->everyFiveMinutes(),
                    Every::TEN_MINUTES => $scheduler->everyTenMinutes(),
                    Every::FIFTEEN_MINUTES => $scheduler->everyFifteenMinutes(),
                    Every::THIRTY_MINUTES => $scheduler->everyThirtyMinutes(),
                    Every::HOUR => $scheduler->hourlyAt($schedule->time ?? '0'),
                    Every::TWO_HOURS => $scheduler->everyTwoHours(),
                    Every::THREE_HOURS => $scheduler->everyThreeHours(),
                    Every::FOUR_HOURS => $scheduler->everyFourHours(),
                    Every::SIX_HOURS => $scheduler->everySixHours(),
                    Every::DAY => $scheduler->dailyAt($schedule->time ?? '00:00'),
                    Every::DAY_TWICE => $scheduler->twiceDaily(),
                    Every::MONTH => $scheduler->monthly(),
                    Every::YEAR => $scheduler->yearly(),
                    default => $scheduler->cron($schedule->schedule),
                };
            }
        });
    }

    private function createSchedule(DiscoveredSchedule $schedule): PendingEventAttributes|CallbackEvent|Event
    {
        if ($schedule->type === Type::JOB) {
            return Scheduler::job($schedule->class);
        }

        if ($schedule->type === Type::COMMAND) {
            return Scheduler::command($schedule->class);
        }

        return Scheduler::call($schedule->method ? [$schedule->class, $schedule->method] : $schedule->class);
    }
}

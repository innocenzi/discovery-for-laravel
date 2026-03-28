<?php

declare(strict_types=1);

namespace Tests\Scheduling;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule as Scheduler;
use Innocenzi\Discovery\ScheduleDiscovery;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tempest\Discovery\DiscoveryItems;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Reflection\ClassReflector;
use Tests\Scheduling\Fixtures\FixtureScheduledCallbacks;

/** @internal */
final class ScheduleDiscoveryTest extends TestCase
{
    #[Test]
    public function finds_schedules_for_selected_every_variants(): void
    {
        $discovery = new ScheduleDiscovery(application: $this->app);
        $discovery->setItems(new DiscoveryItems([]));

        $discovery->discover(
            location: new DiscoveryLocation('App', path: 'src/'),
            class: new ClassReflector(FixtureScheduledCallbacks::class),
        );

        $discovery->apply();

        $events = $this->app->make(Scheduler::class)->events();
        $events_by_name = [];

        foreach ($events as $event) {
            $events_by_name[$event->description] = $event;
        }

        $this->assertArrayHasKey('second', $events_by_name);
        $this->assertArrayHasKey('hourly', $events_by_name);
        $this->assertArrayHasKey('hourly_at', $events_by_name);
        $this->assertArrayHasKey('daily', $events_by_name);
        $this->assertArrayHasKey('daily_at', $events_by_name);

        /** @var Event $second */
        $second = $events_by_name['second'];
        $this->assertSame('* * * * *', $second->expression);
        $this->assertSame(1, $second->repeatSeconds);

        /** @var Event $hourly */
        $hourly = $events_by_name['hourly'];
        $this->assertSame('0 * * * *', $hourly->expression);
        $this->assertNull($hourly->repeatSeconds);

        /** @var Event $hourly_at */
        $hourly_at = $events_by_name['hourly_at'];
        $this->assertSame('15 * * * *', $hourly_at->expression);

        /** @var Event $daily */
        $daily = $events_by_name['daily'];
        $this->assertSame('0 0 * * *', $daily->expression);

        /** @var Event $daily_at */
        $daily_at = $events_by_name['daily_at'];
        $this->assertSame('30 13 * * *', $daily_at->expression);
    }
}

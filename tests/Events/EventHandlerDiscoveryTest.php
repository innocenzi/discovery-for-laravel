<?php

declare(strict_types=1);

namespace Tests\Events;

use Discovery\EventHandlerDiscovery;
use Illuminate\Support\Facades\Event;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\PreCondition;
use PHPUnit\Framework\Attributes\Test;
use Tempest\Discovery\DiscoveryItems;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Reflection\ClassReflector;
use Tests\Events\Fixtures\FixtureEvent;
use Tests\Events\Fixtures\FixtureEventHandler;
use Tests\Events\Fixtures\FixtureUndiscoveredEventHandler;

/** @internal */
final class EventHandlerDiscoveryTest extends TestCase
{
    #[PreCondition]
    protected function before(): void
    {
        FixtureEventHandler::reset();
    }

    #[Test]
    public function finds_event_handlers(): void
    {
        $discovery = new EventHandlerDiscovery(application: $this->app);
        $discovery->setItems(new DiscoveryItems([]));

        $discovery->discover(
            location: new DiscoveryLocation('App', path: 'src/'),
            class: new ClassReflector(FixtureEventHandler::class),
        );

        $discovery->apply();

        Event::dispatch(new FixtureEvent());

        $this->assertSame(1, FixtureEventHandler::$discovered_calls);
        $this->assertSame(0, FixtureEventHandler::$undiscovered_calls);
    }

    #[Test]
    public function ignores_methods_without_the_event_handler_attribute(): void
    {
        $discovery = new EventHandlerDiscovery(application: $this->app);
        $discovery->setItems(new DiscoveryItems([]));

        $discovery->discover(
            location: new DiscoveryLocation('App', path: 'src/'),
            class: new ClassReflector(FixtureUndiscoveredEventHandler::class),
        );

        $discovery->apply();

        Event::dispatch(new FixtureEvent());

        $this->assertSame(0, FixtureUndiscoveredEventHandler::$calls);
    }
}

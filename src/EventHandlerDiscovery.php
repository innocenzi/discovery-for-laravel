<?php

declare(strict_types=1);

namespace Innocenzi\Discovery;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Event;
use Innocenzi\Discovery\Events\DiscoveredEvent;
use Innocenzi\Discovery\Events\EventHandler;
use Tempest\Discovery\Discovery;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Discovery\IsDiscovery;
use Tempest\Reflection\ClassReflector;

final class EventHandlerDiscovery implements Discovery
{
    use IsDiscovery;

    public function __construct(
        private readonly Application $application,
    ) {}

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        foreach ($class->getPublicMethods() as $method) {
            $attribute = $method->getAttribute(EventHandler::class);

            if (! $attribute) {
                continue;
            }

            $this->discoveryItems->add($location, DiscoveredEvent::from($method));
        }
    }

    public function apply(): void
    {
        /** @var DiscoveredEvent $event */
        foreach ($this->discoveryItems as $event) {
            Event::listen($event->event, [$event->class, $event->method]);
        }
    }
}

<?php

namespace Innocenzi\Discovery\Container;

use Illuminate\Container\Attributes\Singleton;
use Illuminate\Contracts\Foundation\Application;
use Tempest\Discovery\Discovery;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Discovery\IsDiscovery;
use Tempest\Reflection\ClassReflector;

final class InitializerDiscovery implements Discovery
{
    use IsDiscovery;

    public function __construct(
        private readonly Application $application,
    ) {}

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        if ($class->implements(Initializer::class)) {
            $this->discoveryItems->add($location, $class);
        }
    }

    public function apply(): void
    {
        /** @var ClassReflector $initializer */
        foreach ($this->discoveryItems as $initializer) {
            $method = $initializer->getMethod('initialize');

            $this->application->bind(
                abstract: $method->getReturnType()->getName(),
                concrete: fn (Application $application) => $application->make($initializer->getName())->initialize($application),
                shared: ! is_null($initializer->getAttribute(Singleton::class)),
            );
        }
    }
}

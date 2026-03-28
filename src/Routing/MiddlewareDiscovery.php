<?php

declare(strict_types=1);

namespace Innocenzi\Discovery\Routing;

use Illuminate\Routing\Router;
use Tempest\Discovery\Discovery;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Discovery\IsDiscovery;
use Tempest\Reflection\ClassReflector;

final class MiddlewareDiscovery implements Discovery
{
    use IsDiscovery;

    public function __construct(
        private readonly Router $router,
    ) {}

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        $attribute = $class->getAttribute(Middleware::class);

        if ($attribute) {
            $this->discoveryItems->add($location, [$attribute->group, $class->getName()]);
        }
    }

    public function apply(): void
    {
        foreach ($this->discoveryItems as [$group, $class]) {
            $this->router->pushMiddlewareToGroup($group, $class);
        }
    }
}

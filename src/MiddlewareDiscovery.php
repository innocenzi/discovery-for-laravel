<?php

declare(strict_types=1);

namespace Discovery;

use Discovery\Routing\Middleware;
use Illuminate\Contracts\Http\Kernel as KernelContract;
use Illuminate\Foundation\Http\Kernel;
use Tempest\Discovery\Discovery;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Discovery\IsDiscovery;
use Tempest\Reflection\ClassReflector;

final class MiddlewareDiscovery implements Discovery
{
    use IsDiscovery;

    /** @param Kernel $kernel */
    public function __construct(
        private readonly KernelContract $kernel,
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
            $this->kernel->appendMiddlewareToGroup($group, $class);
        }
    }
}

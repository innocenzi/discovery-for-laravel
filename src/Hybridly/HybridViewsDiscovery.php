<?php

declare(strict_types=1);

namespace Innocenzi\Discovery\Hybridly;

use Hybridly\Hybridly;
use Tempest\Discovery\DiscoversPath;
use Tempest\Discovery\Discovery;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Discovery\IsDiscovery;
use Tempest\Reflection\ClassReflector;

final class HybridViewsDiscovery implements Discovery, DiscoversPath
{
    use IsDiscovery;

    public function __construct(
        private readonly Hybridly $hybridly,
        private readonly HybridComponentNameResolver $name_resolver,
    ) {}

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        return;
    }

    public function discoverPath(DiscoveryLocation $location, string $path): void
    {
        if (str_ends_with($path, needle: '.view.vue')) {
            $this->discoveryItems->add($location, $path);
            return;
        }

        if (str_ends_with($path, needle: '.layout.vue')) {
            $this->discoveryItems->add($location, $path);
            return;
        }
    }

    public function apply(): void
    {
        foreach ($this->discoveryItems as $path) {
            $component = $this->name_resolver->resolve($path);

            if (str_ends_with($path, needle: '.view.vue')) {
                $this->hybridly->addView($component->path, $component->namespace, $component->identifier);
                continue;
            }

            if (str_ends_with($path, needle: '.layout.vue')) {
                $this->hybridly->addLayout($component->path, $component->namespace, $component->identifier);
                continue;
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace Discovery;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Tempest\Discovery\DiscoversPath;
use Tempest\Discovery\Discovery;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Discovery\IsDiscovery;
use Tempest\Reflection\ClassReflector;

final class ConfigDiscovery implements Discovery, DiscoversPath
{
    use IsDiscovery;

    public function __construct(
        private readonly Application $application,
        private readonly Repository $config,
    ) {}

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        return;
    }

    public function discoverPath(DiscoveryLocation $location, string $path): void
    {
        if (! str_ends_with($path, needle: '.config.php')) {
            return;
        }

        // ignore object configs, as they can't be serialized in the cache by Laravel
        if (! is_array(require $path)) {
            return;
        }

        $this->discoveryItems->add($location, $path);
    }

    public function apply(): void
    {
        if ($this->application->configurationIsCached()) {
            return;
        }

        foreach ($this->discoveryItems as $path) {
            $namespace = str($path)
                ->basename()
                ->chopEnd('.config.php')
                ->toString();

            $this->config->set($namespace, require $path);
        }
    }
}

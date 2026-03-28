<?php

declare(strict_types=1);

namespace Innocenzi\Discovery;

use Psr\Container\ContainerInterface;
use Tempest\Discovery\BootDiscovery;
use Tempest\Discovery\ClearDiscoveryCache;
use Tempest\Discovery\DiscoveryCache;
use Tempest\Discovery\DiscoveryConfig;
use Tempest\Discovery\GenerateDiscoveryCache;

final class Discovery
{
    /** @var \Tempest\Discovery\Discovery[] $discoveries */
    private array $discoveries = [];

    public function __construct(
        private readonly ContainerInterface $container,
        private readonly DiscoveryConfig $config,
        private readonly DiscoveryCache $cache,
    ) {}

    /**
     * Discovers all discoverable items and applies them. This should only be called once.
     */
    public function discover(): void
    {
        $this->discoveries = (new BootDiscovery($this->container, $this->config, $this->cache))();
    }

    /**
     * Generates the Discovery cache according to the configured strategy.
     */
    public function generateDiscoveryCache(): void
    {
        (new GenerateDiscoveryCache())(
            container: $this->container,
            config: $this->config,
            cache: $this->cache,
            discoveries: $this->discoveries,
        );
    }

    /**
     * Clears the Discovery cache.
     */
    public function clearDiscoveryCache(): void
    {
        (new ClearDiscoveryCache())($this->cache);
    }
}

<?php

declare(strict_types=1);

namespace Discovery\Hybridly;

use Discovery\Initializer;
use Illuminate\Container\Attributes\Singleton;
use Psr\Container\ContainerInterface;
use Tempest\Discovery\Composer;

#[Singleton]
final class HybridComponentNameResolverInitializer implements Initializer
{
    public function initialize(ContainerInterface $container): HybridComponentNameResolver
    {
        $root = config('discovery.autoload_path');

        return new DefaultHybridComponentNameResolver(
            composer: new Composer($root),
            root_path: $root,
        );
    }
}

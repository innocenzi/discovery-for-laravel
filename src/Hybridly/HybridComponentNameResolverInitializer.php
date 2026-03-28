<?php

namespace Innocenzi\Discovery\Hybridly;

use Illuminate\Container\Attributes\Singleton;
use Innocenzi\Discovery\Container\Initializer;
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

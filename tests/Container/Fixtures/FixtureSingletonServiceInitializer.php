<?php

declare(strict_types=1);

namespace Tests\Container\Fixtures;

use Illuminate\Container\Attributes\Singleton;
use Innocenzi\Discovery\Container\Initializer;
use Psr\Container\ContainerInterface;

#[Singleton]
final class FixtureSingletonServiceInitializer implements Initializer
{
    public function initialize(ContainerInterface $container): FixtureSingletonService
    {
        return new FixtureSingletonService();
    }
}

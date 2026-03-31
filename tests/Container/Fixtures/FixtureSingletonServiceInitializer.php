<?php

declare(strict_types=1);

namespace Tests\Container\Fixtures;

use Discovery\Initializer;
use Illuminate\Container\Attributes\Singleton;
use Psr\Container\ContainerInterface;

#[Singleton]
final class FixtureSingletonServiceInitializer implements Initializer
{
    public function initialize(ContainerInterface $container): FixtureSingletonService
    {
        return new FixtureSingletonService();
    }
}

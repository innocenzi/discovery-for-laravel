<?php

declare(strict_types=1);

namespace Tests\Container\Fixtures;

use Innocenzi\Discovery\Initializer;
use Psr\Container\ContainerInterface;

final class FixtureServiceInitializer implements Initializer
{
    public function initialize(ContainerInterface $container): FixtureService
    {
        return new FixtureService();
    }
}

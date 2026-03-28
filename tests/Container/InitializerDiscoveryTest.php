<?php

declare(strict_types=1);

namespace Tests\Container;

use Innocenzi\Discovery\Container\InitializerDiscovery;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tempest\Discovery\DiscoveryItems;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Reflection\ClassReflector;
use Tests\Container\Fixtures\FixtureService;
use Tests\Container\Fixtures\FixtureServiceInitializer;
use Tests\Container\Fixtures\FixtureSingletonService;
use Tests\Container\Fixtures\FixtureSingletonServiceInitializer;

/** @internal */
final class InitializerDiscoveryTest extends TestCase
{
    #[Test]
    public function registers_initializers(): void
    {
        $discovery = new InitializerDiscovery(application: $this->app);
        $discovery->setItems(new DiscoveryItems([]));

        $discovery->discover(
            location: new DiscoveryLocation('App', path: 'src/'),
            class: new ClassReflector(FixtureServiceInitializer::class),
        );

        $discovery->discover(
            location: new DiscoveryLocation('App', path: 'src/'),
            class: new ClassReflector(FixtureSingletonServiceInitializer::class),
        );

        $discovery->apply();

        $first = $this->app->make(FixtureService::class);
        $second = $this->app->make(FixtureService::class);

        $this->assertInstanceOf(FixtureService::class, $first);
        $this->assertNotSame($first, $second);

        $singleton_first = $this->app->make(FixtureSingletonService::class);
        $singleton_second = $this->app->make(FixtureSingletonService::class);

        $this->assertSame($singleton_first, $singleton_second);
    }
}

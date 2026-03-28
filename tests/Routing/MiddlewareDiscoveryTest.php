<?php

namespace Tests\Routing;

use Illuminate\Routing\Router;
use Innocenzi\Discovery\Routing\MiddlewareDiscovery;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tempest\Discovery\DiscoveryItems;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Reflection\ClassReflector;
use Tests\Routing\Fixtures\FixtureMiddleware;
use Tests\Routing\Fixtures\IgnoredRoutesClass;

/** @internal */
final class MiddlewareDiscoveryTest extends TestCase
{
    #[Test]
    public function finds_middleware(): void
    {
        $discovery = new MiddlewareDiscovery(router: $this->app->make(Router::class));
        $discovery->setItems(new DiscoveryItems([]));

        $discovery->discover(
            location: new DiscoveryLocation('App', path: 'src/'),
            class: new ClassReflector(FixtureMiddleware::class),
        );

        $discovery->apply();

        $groups = $this->app->make(Router::class)->getMiddlewareGroups();

        $this->assertContains(FixtureMiddleware::class, $groups['api']);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Routing;

use Discovery\MiddlewareDiscovery;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Routing\Router;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tempest\Discovery\DiscoveryItems;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Reflection\ClassReflector;
use Tests\Routing\Fixtures\FixtureMiddleware;

/** @internal */
final class MiddlewareDiscoveryTest extends TestCase
{
    #[Test]
    public function finds_middleware(): void
    {
        $discovery = new MiddlewareDiscovery(kernel: $this->app->make(Kernel::class));
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

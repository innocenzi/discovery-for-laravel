<?php

declare(strict_types=1);

namespace Tests\Routing;

use Illuminate\Routing\Router;
use Innocenzi\Discovery\RouteDiscovery;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tempest\Discovery\DiscoveryItems;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Reflection\ClassReflector;
use Tests\Routing\Fixtures\FixtureRoutesController;

/** @internal */
final class RouteDiscoveryTest extends TestCase
{
    #[Test]
    public function finds_routes(): void
    {
        $discovery = new RouteDiscovery(
            application: $this->app,
            router: $this->app->make(Router::class),
        );

        $discovery->setItems(new DiscoveryItems([]));

        $discovery->discover(
            location: new DiscoveryLocation('App', path: 'src/'),
            class: new ClassReflector(FixtureRoutesController::class),
        );

        $discovery->apply();

        $routes = $this->app->make(Router::class)->getRoutes();

        $get = $routes->getByAction(FixtureRoutesController::class . '@index');
        $post = $routes->getByAction(FixtureRoutesController::class . '@store');

        $this->assertSame('fixture/list', $get->uri());
        $this->assertContains('GET', $get->methods());
        $this->assertSame(FixtureRoutesController::class . '@index', $get->getActionName());

        $this->assertSame('fixture/submit', $post->uri());
        $this->assertContains('POST', $post->methods());
        $this->assertSame(FixtureRoutesController::class . '@store', $post->getActionName());
    }
}

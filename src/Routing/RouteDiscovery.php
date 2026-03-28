<?php

declare(strict_types=1);

namespace Innocenzi\Discovery\Routing;

use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Tempest\Discovery\Discovery;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Discovery\IsDiscovery;
use Tempest\Reflection\ClassReflector;

final class RouteDiscovery implements Discovery
{
    use IsDiscovery;

    public function __construct(
        private readonly Application $application,
        private readonly Router $router,
    ) {}

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        foreach ($class->getPublicMethods() as $method) {
            $route_attributes = $method->getAttributes(Route::class);

            foreach ($route_attributes as $route_attribute) {
                $route = DiscoveredRoute::from(
                    method: $method,
                    route: $route_attribute,
                    decorators: [
                        ...$method->getDeclaringClass()->getAttributes(RouteDecorator::class),
                        ...$method->getAttributes(RouteDecorator::class),
                    ],
                );

                $this->discoveryItems->add($location, $route);
            }
        }
    }

    public function apply(): void
    {
        if ($this->application->routesAreCached()) {
            return;
        }

        /** @var DiscoveredRoute $route */
        foreach ($this->discoveryItems as $route) {
            $this->router
                ->addRoute(
                    methods: [$route->method->value],
                    uri: $route->uri,
                    action: $route->action,
                )
                ->middleware($route->middleware)
                ->withoutMiddleware($route->without_middleware)
                ->where($route->where)
                ->name($route->name);
        }
    }
}

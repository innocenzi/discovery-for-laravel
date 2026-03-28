<?php

namespace Innocenzi\Discovery\Routing;

use Attribute;

/**
 * Removes the specified middleware from this route.
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class WithoutMiddleware implements RouteDecorator
{
    public function __construct(
        private readonly array|string $middleware,
    ) {}

    public function decorate(Route $route): Route
    {
        foreach ((array) $this->middleware as $middleware) {
            $route->without_middleware[] = $middleware;
        }

        return $route;
    }
}

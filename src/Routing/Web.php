<?php

namespace Innocenzi\Discovery\Routing;

use Attribute;

/**
 * Puts the route in the "web" middleware group.
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Web implements RouteDecorator
{
    public function decorate(Route $route): Route
    {
        $route->middleware[] = 'web';

        return $route;
    }
}

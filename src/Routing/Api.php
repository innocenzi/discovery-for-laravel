<?php

namespace Innocenzi\Discovery\Routing;

use Attribute;

/**
 * Puts the route in the "api" middleware group.
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Api implements RouteDecorator
{
    public function decorate(Route $route): Route
    {
        $route->middleware[] = 'api';

        return $route;
    }
}

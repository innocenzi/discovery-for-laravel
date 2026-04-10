<?php

declare(strict_types=1);

namespace Discovery\Routing;

use Attribute;
use Illuminate\Support\Str;

/**
 * Applies a URI and/or a name prefix to the route.
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Prefix implements RouteDecorator
{
    public function __construct(
        private readonly string $uri = '',
        private readonly string $name = '',
    ) {}

    public function decorate(Route $route): Route
    {
        $route->name = rtrim($this->name, characters: '.') . ($route->name ? Str::start($route->name, prefix: '.') : '');

        $prefix = rtrim($this->uri, characters: '/');
        $uri = ltrim($route->uri, characters: '/');

        if ($uri === '') {
            $route->uri = $prefix === '' ? '/' : $prefix;

            return $route;
        }

        $route->uri = $prefix === ''
            ? '/' . $uri
            : $prefix . '/' . $uri;

        return $route;
    }
}

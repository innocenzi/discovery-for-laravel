<?php

declare(strict_types=1);

namespace Innocenzi\Discovery\Routing;

use Tempest\Reflection\MethodReflector;

final class DiscoveredRoute
{
    private function __construct(
        public readonly string $uri,
        public readonly Method $method,
        public readonly string|array $action,
        public readonly array $middleware,
        public readonly array $without_middleware,
        public readonly ?string $name,
        public readonly array $where,
    ) {}

    /** @param array<RouteDecorator> $decorators */
    public static function from(MethodReflector $method, Route $route, array $decorators): self
    {
        foreach ($decorators as $decorator) {
            $route = $decorator->decorate($route);
        }

        return new self(
            uri: $route->uri,
            method: $route->method,
            action: $method->getName() === '__invoke'
                ? $method->getDeclaringClass()->getName()
                : [$method->getDeclaringClass()->getName(), $method->getName()],
            middleware: array_reverse($route->middleware),
            without_middleware: $route->without_middleware,
            name: $route->name,
            where: $route->where,
        );
    }
}

<?php

namespace Innocenzi\Discovery\Routing;

interface Route
{
    /**
     * The HTTP method of the route.
     */
    public Method $method { get; set; }

    /**
     * The URI of the route, relative to the controller's base URI if specified.
     */
    public string $uri { get; set; }

    /**
     * The middleware that should be applied to this route.
     *
     * @var class-string[]
     */
    public array $middleware { get; set; }

    /**
     * The middleware that should be removed from this route.
     *
     * @var class-string[]
     */
    public array $without_middleware { get; set; }

    /**
     * An optional name for the route.
     */
    public ?string $name { get; set; }

    /**
     * The route parameter constraints. The keys are the route segments, and the values are the regular expressions that should be used to validate them.
     *
     * @var array<string, string>
     */
    public array $where { get; set; }
}

<?php

namespace Innocenzi\Discovery\Routing;

use Attribute;

/**
 * Represents a route that should be registered as a DELETE request.
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD)]
final class Delete implements Route
{
    public Method $method = Method::DELETE;

    public function __construct(
        public string $uri,
        public ?string $name = null,
        public array $middleware = [],
        public array $without_middleware = [],
        public array $where = [],
    ) {}
}

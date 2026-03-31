<?php

declare(strict_types=1);

namespace Discovery\Routing;

use Attribute;

/**
 * Represents a route that should be registered as a GET request.
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD)]
final class Get implements Route
{
    public Method $method = Method::GET;

    public function __construct(
        public string $uri,
        public ?string $name = null,
        public array $middleware = [],
        public array $without_middleware = [],
        public array $where = [],
    ) {}
}

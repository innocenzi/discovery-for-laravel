<?php

declare(strict_types=1);

namespace Discovery\Routing;

use Attribute;

/**
 * Represents a route that should be registered as a PUT request.
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD)]
final class Put implements Route
{
    public Method $method = Method::PUT;

    public function __construct(
        public string $uri,
        public ?string $name = null,
        public array $middleware = [],
        public array $without_middleware = [],
        public array $where = [],
    ) {}
}

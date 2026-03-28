<?php

declare(strict_types=1);

namespace Innocenzi\Discovery\Routing;

use Attribute;

/**
 * Registers this middleware in the specified group (by default, the "web" group).
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class Middleware
{
    public function __construct(
        public readonly string $group = 'web',
    ) {}
}

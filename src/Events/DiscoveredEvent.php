<?php

declare(strict_types=1);

namespace Innocenzi\Discovery\Events;

use Tempest\Reflection\MethodReflector;

final class DiscoveredEvent
{
    public function __construct(
        public readonly string $class,
        public readonly string $method,
        public readonly string $event,
    ) {}

    public static function from(MethodReflector $method): self
    {
        return new self(
            class: $method->getDeclaringClass()->getName(),
            method: $method->getName(),
            event: $method->getParameter(key: 0)->getType()->getName(),
        );
    }
}

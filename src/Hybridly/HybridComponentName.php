<?php

namespace Innocenzi\Discovery\Hybridly;

final readonly class HybridComponentName
{
    public function __construct(
        public string $path,
        public string $namespace,
        public string $identifier,
    ) {}
}

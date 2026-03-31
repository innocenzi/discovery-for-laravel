<?php

declare(strict_types=1);

namespace Discovery\Hybridly;

interface HybridComponentNameResolver
{
    /**
     * Resolves the namespace and name of a hybrid view given a path.
     */
    public function resolve(string $path): HybridComponentName;
}

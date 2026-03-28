<?php

declare(strict_types=1);

namespace Innocenzi\Discovery\Container;

use Psr\Container\ContainerInterface;

interface Initializer
{
    /**
     * Initializes the given class, returning an instance of it. To register it as a singleton, add the {@see Illuminate\Container\Attributes\Singleton} attribute to this class.
     *
     * **It is important to type the return type of this method to the class being initialized**, so that it can be properly registered in the container.
     */
    public function initialize(ContainerInterface $container): mixed;
}
